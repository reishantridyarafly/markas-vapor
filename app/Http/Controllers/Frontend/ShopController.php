<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Catalog;
use App\Models\Product;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::with('catalog')->where('status', 0)
            ->where('stock', '>', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $products->transform(function ($product) {
            $product->average_rating = $product->ratings->avg('rating') ?? 0;
            $product->ratings_count = $product->ratings->count();
            return $product;
        });

        $catalogs = Catalog::whereHas('products', function ($query) {
            $query->where('status', 0)
                ->where('stock', '>', 0);
        })->orderBy('name', 'asc')->get();

        return view('frontend.shop.index', compact(['products', 'catalogs']));
    }

    public function detail($slug)
    {
        $product = Product::with('catalog')->where('slug', $slug)->first();

        if (!$product) {
            abort(404);
        }

        // Calculate product ratings
        if ($product) {
            $product->average_rating = $product->ratings->avg('rating') ?? 0;
            $product->ratings_count = $product->ratings->count();
        }

        $user = auth()->user();
        $userId = $user ? $user->id : null;

        $hasPurchased = false;
        $hasRated = false;

        if ($userId) {
            $hasPurchased = DB::table('transaction_details')
                ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
                ->where('transaction_details.product_id', $product->id)
                ->where('transactions.user_id', $userId)
                ->whereIn('transactions.status', ['completed', 'refund'])
                ->exists();

            $hasRated = DB::table('ratings')
                ->where('product_id', $product->id)
                ->where('user_id', $userId)
                ->exists();
        }

        $rating_reviews = Rating::with('user')
            ->where('product_id', $product->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $recommendedProducts = $this->getRecommendedProducts($product->id);

        return view('frontend.shop.detail', compact([
            'product',
            'hasPurchased',
            'hasRated',
            'rating_reviews',
            'recommendedProducts'
        ]));
    }

    private function getRecommendedProducts($productId)
    {
        // Step 1: Find products frequently bought together
        $frequentlyBoughtTogether = DB::table('transaction_details as td1')
            ->join('transaction_details as td2', 'td1.transaction_id', '=', 'td2.transaction_id')
            ->join('transactions as t', 'td1.transaction_id', '=', 't.id')
            ->where('td1.product_id', $productId)
            ->where('td2.product_id', '!=', $productId)
            ->whereIn('t.status', ['completed', 'refund'])
            ->select(
                'td2.product_id',
                DB::raw('COUNT(DISTINCT td1.transaction_id) as purchase_frequency'),
                DB::raw('SUM(td2.quantity) as total_quantity_sold')
            )
            ->groupBy('td2.product_id')
            ->orderByDesc('purchase_frequency')
            ->orderByDesc('total_quantity_sold')
            ->take(15) // Ambil 15 teratas untuk filtering lebih lanjut
            ->get();

        $recommendedProductIds = [];

        if ($frequentlyBoughtTogether->isNotEmpty()) {
            // Jika ada produk yang sering dibeli bersamaan
            $recommendedProductIds = $frequentlyBoughtTogether->pluck('product_id')->toArray();
        } else {
            // Fallback: Ambil produk populer dari kategori yang sama atau semua kategori
            $currentProduct = Product::find($productId);

            // Coba ambil dari kategori yang sama dulu
            $sameCategoryProducts = DB::table('transaction_details')
                ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
                ->join('products', 'transaction_details.product_id', '=', 'products.id')
                ->where('products.catalog_id', $currentProduct->catalog_id)
                ->where('transaction_details.product_id', '!=', $productId)
                ->whereIn('transactions.status', ['completed', 'refund'])
                ->select(
                    'transaction_details.product_id',
                    DB::raw('COUNT(DISTINCT transactions.id) as purchase_count'),
                    DB::raw('SUM(transaction_details.quantity) as total_sold')
                )
                ->groupBy('transaction_details.product_id')
                ->orderByDesc('purchase_count')
                ->orderByDesc('total_sold')
                ->take(10)
                ->get();

            if ($sameCategoryProducts->isNotEmpty()) {
                $recommendedProductIds = $sameCategoryProducts->pluck('product_id')->toArray();
            } else {
                // Jika kategori sama tidak ada, ambil produk populer global
                $popularProducts = DB::table('transaction_details')
                    ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
                    ->where('transaction_details.product_id', '!=', $productId)
                    ->whereIn('transactions.status', ['completed', 'refund'])
                    ->select(
                        'transaction_details.product_id',
                        DB::raw('COUNT(DISTINCT transactions.id) as purchase_count'),
                        DB::raw('SUM(transaction_details.quantity) as total_sold')
                    )
                    ->groupBy('transaction_details.product_id')
                    ->orderByDesc('purchase_count')
                    ->orderByDesc('total_sold')
                    ->take(10)
                    ->get();

                $recommendedProductIds = $popularProducts->pluck('product_id')->toArray();
            }
        }

        // Step 2: Load products dengan rating dan filter hanya yang available
        $recommendedProducts = Product::with(['ratings', 'catalog'])
            ->whereIn('id', $recommendedProductIds)
            ->where('status', 0) // Hanya produk aktif
            ->where('stock', '>', 0) // Hanya produk yang ada stoknya
            ->get()
            ->map(function ($product) {
                $product->average_rating = $product->ratings->avg('rating') ?? 0;
                $product->ratings_count = $product->ratings->count();
                return $product;
            });

        // Step 3: Sort berdasarkan kombinasi rating dan popularitas
        $recommendedProducts = $recommendedProducts->sortByDesc(function ($product) {
            // Formula: (average_rating * 0.6) + (min(ratings_count, 50) / 50 * 5 * 0.4)
            // 60% dari rating, 40% dari jumlah review (max 50 review = skor penuh)
            $ratingScore = $product->average_rating * 0.6;
            $popularityScore = (min($product->ratings_count, 50) / 50) * 5 * 0.4;
            return $ratingScore + $popularityScore;
        })->take(10); // Ambil 10 produk terbaik

        return $recommendedProducts->values();
    }

    private function getRecommendationStats($productId)
    {
        $stats = DB::table('transaction_details as td1')
            ->join('transaction_details as td2', 'td1.transaction_id', '=', 'td2.transaction_id')
            ->join('transactions as t', 'td1.transaction_id', '=', 't.id')
            ->join('products as p', 'td2.product_id', '=', 'p.id')
            ->where('td1.product_id', $productId)
            ->where('td2.product_id', '!=', $productId)
            ->whereIn('t.status', ['completed', 'refund'])
            ->select(
                'p.name',
                'td2.product_id',
                DB::raw('COUNT(DISTINCT td1.transaction_id) as bought_together_count'),
                DB::raw('SUM(td2.quantity) as total_quantity'),
                DB::raw('COUNT(DISTINCT t.user_id) as unique_customers')
            )
            ->groupBy('td2.product_id', 'p.name')
            ->orderByDesc('bought_together_count')
            ->take(20)
            ->get();

        return [
            'total_combinations' => $stats->count(),
            'recommendations' => $stats
        ];
    }

    public function catalog($slug)
    {
        $catalogs = Catalog::whereHas('products', function ($query) {
            $query->where('status', 0)
                ->where('stock', '>', 0);
        })->orderBy('name', 'asc')->get();

        $catalogId = Catalog::where('slug', $slug)->first()->id;

        $products = Product::with('catalog')
            ->where('catalog_id', $catalogId)
            ->where('status', 0)
            ->where('stock', '>', 0)
            ->orderBy('created_at', 'asc')
            ->paginate(12);

        return view('frontend.shop.index', compact(['catalogs', 'products']));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('search');

        $catalogs = Catalog::whereHas('products', function ($query) {
            $query->where('status', 0)
                ->where('stock', '>', 0);
        })->orderBy('name', 'asc')->get();

        $products = Product::with('catalog')
            ->where('name', 'LIKE', "%{$keyword}%")
            ->where('status', 0)
            ->where('stock', '>', 0)
            ->orderBy('created_at', 'asc')
            ->paginate(12);

        return view('frontend.shop.index', compact(['catalogs', 'products']));
    }
}

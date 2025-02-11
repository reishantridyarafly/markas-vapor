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

        $rating_reviews = Rating::with('user')->where('product_id', $product->id)->orderBy('created_at', 'desc')->get();

        $popularProductViews = DB::table('product_views')
            ->select('product_id', DB::raw('COUNT(*) as views'))
            ->where('product_id', '!=', $product->id)
            ->groupBy('product_id')
            ->orderByDesc('views')
            ->take(10)
            ->get();

        $recommendedProducts = [];
        foreach ($popularProductViews as $popularProductView) {
            $recommendedProduct = Product::with('ratings')->find($popularProductView->product_id);
            if ($recommendedProduct) {
                $recommendedProduct->average_rating = $recommendedProduct->ratings->avg('rating') ?? 0;
                $recommendedProduct->ratings_count = $recommendedProduct->ratings->count();
                $recommendedProducts[] = $recommendedProduct;
            }
        }

        return view('frontend.shop.detail', compact(['product', 'hasPurchased', 'hasRated', 'rating_reviews', 'recommendedProducts']));
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

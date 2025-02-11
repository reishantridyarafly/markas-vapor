<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Catalog;
use App\Models\Product;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BerandaController extends Controller
{
    public function index()
    {
        $new_products = Product::with('ratings')
            ->where('products.status', 0)
            ->where('products.stock', '>', 0)
            ->orderBy('created_at', 'desc')
            ->take(15)
            ->get();

        $new_products->transform(function ($product) {
            $product->average_rating = $product->ratings->avg('rating') ?? 0;
            $product->ratings_count = $product->ratings->count();
            return $product;
        });

        $most_purchased_products = DB::table('transaction_details')
            ->select(
                'products.id',
                'products.cover_photo',
                'products.name',
                'products.slug',
                'products.before_price',
                'products.after_price',
                'products.catalog_id',
                'catalog.name as catalog_name',
                'catalog.slug as catalog_slug',
                DB::raw('(SELECT photo_name FROM product_photos WHERE product_id = products.id LIMIT 1) as photo_name'),
                DB::raw('SUM(transaction_details.quantity) as total_quantity')
            )
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->join('catalog', 'products.catalog_id', '=', 'catalog.id')
            ->groupBy(
                'products.id',
                'products.cover_photo',
                'products.name',
                'products.slug',
                'products.before_price',
                'products.after_price',
                'products.catalog_id',
                'catalog.name',
                'catalog.slug',
            )
            ->orderBy('total_quantity', 'asc')
            ->take(15)
            ->get();

        $productIds = $most_purchased_products->pluck('id');
        $ratings = Rating::whereIn('product_id', $productIds)->get()->groupBy('product_id');

        $most_purchased_products->transform(function ($product) use ($ratings) {
            if (isset($ratings[$product->id])) {
                $product->average_rating = $ratings[$product->id]->avg('rating') ?? 0;
                $product->ratings_count = $ratings[$product->id]->count();
            } else {
                $product->average_rating = 0;
                $product->ratings_count = 0;
            }
            return $product;
        });


        $testimoni = Rating::with('user')->where('rating', '>', '3.50')->orderBy('created_at', 'desc')->get();

        $catalog = Catalog::whereHas('products', function ($query) {
            $query->where('status', 0)
                ->where('stock', '>', 0);
        })->orderBy('name', 'asc')->get();

        return view('frontend.beranda.index', compact(['new_products', 'most_purchased_products', 'testimoni', 'catalog']));
    }
}

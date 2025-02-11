<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WistlistController extends Controller
{
    public function index()
    {
        $wishlist = Wishlist::orderBy('created_at', 'asc')->where('user_id', auth()->user()->id)->get();
        return view('frontend.wishlist.index', compact('wishlist'));
    }

    public function store(Request $request)
    {
        $existingWishlistItem = Wishlist::where('user_id', auth()->user()->id)
            ->where('product_id', $request->id)
            ->first();

        if ($existingWishlistItem) {
            return response()->json(['message' => 'Produk sudah ada di wishlist', 'icon' => 'warning'], 200);
        }

        $wishlist = new Wishlist();
        $wishlist->user_id = auth()->user()->id;
        $wishlist->product_id = $request->id;
        $wishlist->save();

        return response()->json(['message' => 'Wishlist berhasil disimpan', 'icon' => 'success'], 201);
    }


    public function destroy(Request $request)
    {
        $wishlist = Wishlist::where('id', $request->id)
            ->where('user_id', auth()->user()->id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return response()->json(['message' => 'Wishlist berhasil dihapus', 'icon' => 'success'], 200);
        } else {
            return response()->json(['message' => 'Item tidak ditemukan di wishlist', 'icon' => 'error'], 404);
        }
    }
}

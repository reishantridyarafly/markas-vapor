<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{

    // Menampilakan Data Keranjang
    public function index()
    {
        $userId = auth()->id();
        $cart = Cart::where('user_id', $userId)->first();

        if ($cart) {
            $items = $cart->items()->with('product')->get();
        } else {
            $items = collect();
        }
        return view('frontend.cart.index', compact(['items']));
    }

    // Menambahkan produk ke keranjang
    public function addCart($id, Request $request)
    {
        $product = Product::findOrFail($id);
        $userId = auth()->id();
        $cart = Cart::firstOrCreate(['user_id' => $userId]);

        $cartItem = $cart->items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            $cartItem->quantity += $request->qty;
            $cartItem->save();
        } else {

            // Menyimpan ke database
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $request->qty,
                'price' => $product->after_price
            ]);
        }

        return response()->json(['message' => 'Produk berhasil disimpan']);
    }

    // Menampilkan jumlah data (count) keranjang
    public function getCartItemCount()
    {
        $userId = auth()->id();
        $cart = Cart::where('user_id', $userId)->first();
        $itemCount = 0;

        if ($cart) {
            $itemCount = $cart->items()->sum('quantity');
        }

        return response()->json(['count' => $itemCount]);
    }

    // Update jumlah produk di keranjang
    public function updateCartItem(Request $request, $id)
    {
         // Menyimpan ke database
        $cartItem = CartItem::findOrFail($id);
        $cartItem->quantity = $request->input('quantity');
        $cartItem->save();

        return response()->json(['message' => 'Keranjang berhasil diperbarui']);
    }

    // hapus produk dalam keranjang
    public function deleteCartItem($id)
    {
         // Menyimpan ke database
        $cartItem = CartItem::findOrFail($id);
        $cartItem->delete();

        return response()->json(['message' => 'Item berhasil dihapus dari keranjang']);
    }
}

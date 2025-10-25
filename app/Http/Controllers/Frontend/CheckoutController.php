<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\BankAccount;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    public function directCheckout(Request $request)
    {
        $product = Product::find($request->id);
        $addresses = Address::where('user_id', auth()->user()->id)->get();
        $qty = $request->qty;
        $weight = $qty * $request->weight;
        $rekening = BankAccount::all();
        $subtotal = $qty * $product->after_price;
        return view('frontend.checkout.direct', compact(['qty', 'weight', 'product', 'addresses', 'rekening', 'subtotal']));
    }

    public function getAddressDetails($id)
    {
        $address = Address::find($id);

        if (!$address) {
            return response()->json([
                'success' => false,
                'message' => 'Alamat tidak ditemukan'
            ], 404);
        }

        if ($address->user_id != auth()->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'province_id' => $address->provinsi_id,
            'province_name' => $address->province_name,
            'district_id' => $address->district_id,
            'district_name' => $address->district_name,
            'subdistrict_id' => $address->subdistrict_id,
            'subdistrict_name' => $address->subdistrict_name,
            'street' => $address->street,
            'detail_address' => $address->detail_address,
            'name' => $address->name,
            'telephone' => $address->telephone,
        ]);
    }

    public function checkOngkir(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'district_id' => 'required',
                'courier' => 'required|in:jne,pos,tiki',
                'weight' => 'required|numeric|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'shipping_cost' => '<option value="">Data tidak lengkap</option>'
                ]);
            }

            $weight = $request->input('weight');

            if ($weight < 1) {
                $weight = 1;
            }

            $response = Http::asForm()->withHeaders([
                'Accept' => 'application/json',
                'key' => env('RAJAONGKIR_API_KEY'),
            ])->post('https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', [
                'origin' => 1224,
                'destination' => $request->input('district_id'),
                'weight' => $weight,
                'courier' => $request->input('courier'),
            ]);

            if ($response->successful()) {
                $data = $response->json()['data'] ?? [];

                $options = '<option value="">Pilih Layanan Pengiriman</option>';

                if (!empty($data)) {
                    foreach ($data as $item) {
                        $courierName = strtoupper($item['code']);
                        $service = $item['service'];
                        $description = $item['description'];
                        $cost = $item['cost'];
                        $etd = $item['etd'];

                        $label = "{$courierName} - {$service} ({$description}) - Rp " . number_format($cost, 0, ',', '.') . " - Estimasi: {$etd}";

                        $options .= "<option value='{$cost}' data-service='{$service}' data-courier='{$courierName}' data-etd='{$etd}'>{$label}</option>";
                    }
                } else {
                    $options = '<option value="">Tidak ada layanan tersedia</option>';
                }

                return response()->json([
                    'success' => true,
                    'ongkir' => $options
                ]);
            }

            return response()->json([
                'success' => false,
                'ongkir' => '<option value="">Gagal memuat data ongkir</option>'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'ongkir' => '<option value="">Terjadi kesalahan</option>',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function store(Request $request)
    {
        $validated = Validator::make(
            $request->all(),
            [
                'address' => 'required',
                'courier' => 'required|string',
                'shipping_cost' => 'required|string',
                'transfer_proof' => 'required|max:5120|image|mimes:jpg,png,jpeg,webp,svg|file',
            ],
            [
                'address.required' => 'Silakan pilih alamat terlebih dahulu.',
                'courier.required' => 'Silakan pilih kurir terlebih dahulu.',
                'shipping_cost.required' => 'Silakan pilih ongkos kirim terlebih dahulu.',
                'transfer_proof.required' => 'Silakan isi bukti pembayaran terlebih dahulu.',
                'transfer_proof.image' => 'File harus berupa gambar.',
                'transfer_proof.mimes' => 'Ekstensi file harus berupa: jpg, png, jpeg, webp, atau svg.',
                'transfer_proof.file' => 'File harus berupa gambar.',
                'transfer_proof.max' => 'Ukuran file tidak boleh lebih dari 5 MB.',
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            if ($request->hasFile('transfer_proof')) {
                $file = $request->file('transfer_proof');
                $randomFileName = uniqid() . '.' . $file->getClientOriginalExtension();
                $request->file('transfer_proof')->storeAs('bukti_pembayaran/', $randomFileName, 'public');

                if ($file->isValid()) {
                    $transaction = new Transaction();
                    $transaction->code = $this->generateTransactionCode();
                    $transaction->transaction_date = now();
                    $transaction->user_id = auth()->user()->id;
                    $transaction->customer_name = auth()->user()->first_name . ' ' .  auth()->user()->last_name;
                    $transaction->address_id  = $request->address;
                    $transaction->note  = $request->note;
                    $transaction->shipping_cost  = $request->shipping_cost;
                    $transaction->status = 'pending';
                    $transaction->type_transaction = 'online';
                    $transaction->type_payment = 'transfer';
                    $transaction->transfer_proof = $randomFileName;
                    $transaction->courier = $request->courier;
                    $transaction->discount = $request->discount;
                    $subtotal_price = $request->subtotal;
                    $discount_percentage = $request->discount;
                    $discount_amount = $subtotal_price * ($discount_percentage / 100);
                    $transaction->discount = $discount_amount;
                    $transaction->total_price = $request->total;
                    $transaction->save();

                    $transaction->details()->create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $request->product_id,
                        'quantity' => $request->qty,
                        'unit_price' => $request->unit_price,
                        'total_price' => $request->total,
                    ]);

                    $product = Product::find($request->product_id);
                    if ($product) {
                        $product->stock -= $request->qty;
                        $product->save();
                    } else {
                        return response()->json(['message' => 'Produk tidak ditemukan'], 404);
                    }

                    return response()->json(['message' => 'Transaksi berhasil ditambahkan']);
                }
            }
        }
    }

    private function generateTransactionCode()
    {
        $prefix = 'T';
        $date = now()->format('Ymd');

        $lastTransaction = Transaction::latest()->first();
        $lastCode = $lastTransaction ? substr($lastTransaction->code, -4) : '0000';
        $nextCode = str_pad(intval($lastCode) + 1, 4, '0', STR_PAD_LEFT);

        return $prefix . $date . $nextCode;
    }

    public function cartCheckout(Request $request)
    {
        $userId = auth()->id();
        $selectedItems = $request->input('selected_items', []);

        $cart = Cart::where('user_id', $userId)->first();

        if (!$cart) {
            return redirect()->route('cart.index', $userId)->with('error', 'Keranjang belanja tidak ditemukan.');
        }

        $items = $cart->items()
            ->whereIn('id', $selectedItems)
            ->with('product')
            ->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index', $userId)->with('error', 'Pilih setidaknya satu item untuk checkout.');
        }

        $addresses = Address::where('user_id', auth()->user()->id)->get();

        $rekening = BankAccount::all();

        $subtotal = $items->sum(function ($item) {
            return $item->quantity * $item->product->after_price;
        });

        $totalWeight = $items->sum(function ($row) {
            return $row->quantity * $row->product->weight;
        });

        return view('frontend.checkout.cart', compact('items', 'addresses', 'rekening', 'subtotal', 'totalWeight'));
    }

    public function storeCart(Request $request)
    {
        $validated = Validator::make(
            $request->all(),
            [
                'address' => 'required',
                'courier' => 'required|string',
                'shipping_cost' => 'required|string',
                'transfer_proof' => 'required|max:5120|image|mimes:jpg,png,jpeg,webp,svg|file',
            ],
            [
                'address.required' => 'Silakan pilih alamat terlebih dahulu.',
                'courier.required' => 'Silakan pilih kurir terlebih dahulu.',
                'shipping_cost.required' => 'Silakan pilih ongkos kirim terlebih dahulu.',
                'transfer_proof.required' => 'Silakan isi bukti pembayaran terlebih dahulu.',
                'transfer_proof.image' => 'File harus berupa gambar.',
                'transfer_proof.mimes' => 'Ekstensi file harus berupa: jpg, png, jpeg, webp, atau svg.',
                'transfer_proof.file' => 'File harus berupa gambar.',
                'transfer_proof.max' => 'Ukuran file tidak boleh lebih dari 5 MB.',
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            if ($request->hasFile('transfer_proof')) {
                $file = $request->file('transfer_proof');
                $randomFileName = uniqid() . '.' . $file->getClientOriginalExtension();
                $request->file('transfer_proof')->storeAs('bukti_pembayaran/', $randomFileName, 'public');

                if ($file->isValid()) {
                    $transaction = new Transaction();
                    $transaction->code = $this->generateTransactionCode();
                    $transaction->transaction_date = now();
                    $transaction->user_id = auth()->user()->id;
                    $transaction->customer_name = auth()->user()->first_name . ' ' .  auth()->user()->last_name;
                    $transaction->address_id  = $request->address;
                    $transaction->note  = $request->note;
                    $transaction->shipping_cost  = $request->shipping_cost;
                    $transaction->status = 'pending';
                    $transaction->type_transaction = 'online';
                    $transaction->type_payment = 'transfer';
                    $transaction->transfer_proof = $randomFileName;
                    $transaction->courier = $request->courier;
                    $transaction->discount = $request->discount;
                    $subtotal_price = $request->subtotal;
                    $discount_percentage = $request->discount;
                    $discount_amount = $subtotal_price * ($discount_percentage / 100);
                    $transaction->discount = $discount_amount;
                    $transaction->total_price = $request->total;
                    $transaction->save();

                    if (is_array($request->product_id)) {
                        $purchasedProductIds = [];
                        foreach ($request->product_id as $key => $product_id) {
                            $transaction_detail = new TransactionDetail();
                            $transaction_detail->transaction_id = $transaction->id;
                            $transaction_detail->product_id = $product_id;
                            $transaction_detail->quantity = $request->qty[$key];
                            $transaction_detail->unit_price = $request->price[$key];
                            $transaction_detail->total_price = $request->price[$key] * $request->qty[$key];
                            $transaction_detail->save();

                            $product = Product::find($product_id);
                            $product->stock -= $request->qty[$key];
                            $product->save();

                            $purchasedProductIds[] = $product_id;
                        }
                    } else {
                        return response()->json(['errors' => ['product_id' => 'Invalid product data']], 422);
                    }

                    $userCart = Cart::where('user_id', auth()->id())->first();

                    if ($userCart) {
                        CartItem::where('cart_id', $userCart->id)
                            ->whereIn('product_id', $purchasedProductIds)
                            ->delete();

                        if ($userCart->items()->count() == 0) {
                            $userCart->delete();
                        }
                    }

                    return response()->json(['message' => 'Transaksi berhasil ditambahkan']);
                }
            }
        }
    }
}

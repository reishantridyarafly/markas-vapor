<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Catalog;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $product = Product::orderBy('name', 'asc')->get();
            return DataTables::of($product)
                ->addIndexColumn()
                ->addColumn('catalog', function ($data) {
                    return $data->catalog->name;
                })
                ->addColumn('after_price', function ($data) {
                    return 'Ro ' . number_format($data->after_price, 0, ',', '.');
                })
                ->addColumn('status', function ($data) {
                    $checked = $data->status == 0 ? 'checked' : '';
                    return '<div class="form-check form-switch">
                                <input class="form-check-input status-toggle" type="checkbox" role="switch" data-id="' . $data->id . '" ' . $checked . '>
                                <label class="form-check-label" for="status">' . ($data->status == 0 ? 'Aktif' : 'Tidak Aktif') . '</label>
                            </div>';
                })
                ->addColumn('name', function ($data) {
                    return '<span class="editable" data-id="' . $data->id . '">' . $data->name . '</span>';
                })
                ->addColumn('action', function ($data) {
                    return '<div class="hstack gap-2 justify-content-end">
                                <a href="' . route('shop.detail', $data->slug) . '" target="_blank" class="avatar-text avatar-md">
                                    <i class="feather feather-eye"></i>
                                </a>
                                <div class="dropdown">
                                    <a href="javascript:void(0)" class="avatar-text avatar-md" data-bs-toggle="dropdown" data-bs-offset="0,21">
                                        <i class="feather feather-more-horizontal"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="' . route('product.edit', $data->id) . '" class="dropdown-item">
                                                <i class="feather feather-edit-3 me-3"></i>
                                                <span>Edit</span>
                                            </a>
                                        </li>
                                        <li>
                                            <button class="dropdown-item" id="btnDelete" data-id="' . $data->id . '">
                                                <i class="feather feather-trash-2 me-3"></i>
                                                <span>Hapus</span>
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>';
                })
                ->rawColumns(['status', 'name', 'action'])
                ->make(true);
        }
        return view('backend.product.index');
    }

    public function create()
    {
        $catalog = Catalog::orderBy('name', 'asc')->get();
        return view('backend.product.add', compact('catalog'));
    }

    public function store(Request $request)
    {
        $validated = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|unique:products,name',
                'after_price' => 'required',
                'stock' => 'required|string',
                'weight' => 'required',
                'cover_photo' => 'required|mimes:jpg,png,jpeg,webp,svg|file|max:5120',
                'photo' => 'required|max:5120',
                'photo.*' => 'image|mimes:jpg,png,jpeg,webp,svg|file|max:5120',
                'catalog' => 'required|string',
            ],
            [
                'name.required' => 'Silakan isi nama terlebih dahulu.',
                'name.string' => 'Nama harus berupa teks.',
                'name.unique' => 'Nama sudah tersedia.',
                'weight.required' => 'Silakan isi berat terlebih dahulu.',
                'after_price.required' => 'Silakan isi harga terlebih dahulu.',
                'stock.required' => 'Silakan isi stok terlebih dahulu.',
                'stock.string' => 'Stok harus berupa teks.',
                'weight.required' => 'Silakan isi berat terlebih dahulu.',
                'catalog.required' => 'Silakan pilih Katalog terlebih dahulu.',
                'catalog.string' => 'Katalog harus berupa teks.',
                'cover_photo.required' => 'Silakan isi cover foto terlebih dahulu.',
                'cover_photo.image' => 'File harus berupa gambar.',
                'cover_photo.mimes' => 'Ekstensi file harus berupa: jpg, png, jpeg, webp, atau svg.',
                'cover_photo.file' => 'File harus berupa gambar.',
                'cover_photo.max' => 'Ukuran file tidak boleh lebih dari 5 MB.',
                'photo.required' => 'Silakan isi foto terlebih dahulu.',
                'photo.image' => 'File harus berupa gambar.',
                'photo.mimes' => 'Ekstensi file harus berupa: jpg, png, jpeg, webp, atau svg.',
                'photo.file' => 'File harus berupa gambar.',
                'photo.max' => 'Ukuran file tidak boleh lebih dari 5 MB.',
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        }

        $product = new Product();
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->description = $request->description;
        $product->short_description = $request->short_description;
        $product->before_price = $request->before_price ? str_replace(['Rp', ' ', '.'], '', $request->before_price) : null;
        $product->after_price = str_replace(['Rp', ' ', '.'], '', $request->after_price);
        $product->stock = $request->stock;
        $product->weight = $request->weight;
        $product->catalog_id = $request->catalog;

        if ($request->hasFile('cover_photo')) {
            $coverPhoto = $request->file('cover_photo');
            if (!$coverPhoto->isValid()) {
                return response()->json(['error' => 'File tidak valid.']);
            }

            $coverPhotoName = time() . '_cover_' . $coverPhoto->getClientOriginalName();
            if (Storage::disk('public')->putFileAs('uploads/cover', $coverPhoto, $coverPhotoName)) {
                $product->cover_photo = $coverPhotoName;
            } else {
                return response()->json(['error' => 'Gagal menyimpan cover photo']);
            }
        }


        $product->save();

        if ($request->hasFile('photo')) {
            foreach ($request->file('photo') as $image) {
                if (!$image->isValid()) {
                    return response()->json(['error' => 'File foto tidak valid.']);
                }
                $imageName = time() . '_' . $image->getClientOriginalName();

                try {
                    $directory = 'uploads/products';
                    if (!Storage::disk('public')->exists($directory)) {
                        Storage::disk('public')->makeDirectory($directory);
                    }

                    Storage::disk('public')->putFileAs($directory, $image, $imageName);
                    $product->photos()->create(['photo_name' => $imageName]);
                } catch (\Exception $e) {
                    return response()->json(['error' => 'Gagal menyimpan foto produk: ' . $e->getMessage()]);
                }
            }
        }


        return response()->json(['success' => 'Data berhasil disimpan']);
    }


    public function edit($id)
    {
        $product = Product::find($id);
        $catalog = Catalog::orderBy('name', 'asc')->get();
        return view('backend.product.edit', compact('product', 'catalog'));
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $validated = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|unique:products,name,' . $id,
                'after_price' => 'required',
                'stock' => 'required|string',
                'weight' => 'required',
                'cover_photo' => 'mimes:jpg,png,jpeg,webp,svg|file|max:5120',
                'photo' => 'max:5120',
                'photo.*' => 'image|mimes:jpg,png,jpeg,webp,svg|file|max:5120',
                'catalog' => 'required|string',
            ],
            [
                'name.required' => 'Silakan isi nama terlebih dahulu.',
                'name.string' => 'Nama harus berupa teks.',
                'name.unique' => 'Nama sudah tersedia.',
                'weight.required' => 'Silakan isi berat terlebih dahulu.',
                'after_price.required' => 'Silakan isi harga terlebih dahulu.',
                'stock.required' => 'Silakan isi stok terlebih dahulu.',
                'stock.string' => 'Stok harus berupa teks.',
                'weight.required' => 'Silakan isi berat terlebih dahulu.',
                'catalog.required' => 'Silakan pilih Katalog terlebih dahulu.',
                'catalog.string' => 'Katalog harus berupa teks.',
                'cover_photo.required' => 'Silakan isi cover foto terlebih dahulu.',
                'cover_photo.image' => 'File harus berupa gambar.',
                'cover_photo.mimes' => 'Ekstensi file harus berupa: jpg, png, jpeg, webp, atau svg.',
                'cover_photo.file' => 'File harus berupa gambar.',
                'cover_photo.max' => 'Ukuran file tidak boleh lebih dari 5 MB.',
                'photo.required' => 'Silakan isi foto terlebih dahulu.',
                'photo.image' => 'File harus berupa gambar.',
                'photo.mimes' => 'Ekstensi file harus berupa: jpg, png, jpeg, webp, atau svg.',
                'photo.file' => 'File harus berupa gambar.',
                'photo.max' => 'Ukuran file tidak boleh lebih dari 5 MB.',
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            $product = Product::find($id);

            $product->name = $request->name;
            $product->slug = Str::slug($request->name);
            $product->description = $request->description;
            $product->short_description = $request->short_description;
            $product->before_price = $request->before_price ? str_replace(['Rp', ' ', '.'], '', $request->before_price) : null;
            $product->after_price = str_replace(['Rp', ' ', '.'], '', $request->after_price);
            $product->stock = $request->stock;
            $product->weight = $request->weight;
            $product->catalog_id = $request->catalog;

            if ($request->hasFile('cover_photo')) {
                if ($product->cover_photo) {
                    Storage::disk('public')->delete('uploads/cover/' . $product->cover_photo);
                }

                $coverPhoto = $request->file('cover_photo');
                if (!$coverPhoto->isValid()) {
                    return response()->json(['error' => 'Cover photo tidak valid.']);
                }

                $coverPhotoName = time() . '_cover_' . $coverPhoto->getClientOriginalName();

                if (!Storage::disk('public')->exists('uploads/cover')) {
                    Storage::disk('public')->makeDirectory('uploads/cover');
                }

                Storage::disk('public')->putFileAs('uploads/cover', $coverPhoto, $coverPhotoName);
                $product->cover_photo = $coverPhotoName;
            }

            $product->save();

            if ($request->hasFile('photo')) {
                $this->deleteProductImages($product);

                foreach ($request->file('photo') as $image) {
                    if (!$image->isValid()) {
                        return response()->json(['error' => 'Beberapa gambar produk tidak valid.']);
                    }

                    $imageName = time() . '_' . $image->getClientOriginalName();

                    if (!Storage::disk('public')->exists('uploads/products')) {
                        Storage::disk('public')->makeDirectory('uploads/products');
                    }

                    Storage::disk('public')->putFileAs('uploads/products', $image, $imageName);
                    $product->photos()->create(['photo_name' => $imageName]);
                }
            }


            return response()->json(['success' => 'Data berhasil disimpan']);
        }
    }

    private function deleteProductImages($product)
    {
        foreach ($product->photos as $photo) {
            Storage::delete('uploads/products/' . $photo->photo_name);
            $photo->delete();
        }
    }

    public function destroy(Request $request)
    {
        $product = Product::findOrFail($request->id);
        if ($product->cover_photo) {
            Storage::delete('uploads/cover/' . $product->cover_photo);
        }
        $this->deleteProductImages($product);
        $product->delete();

        return response()->json(['message' => 'Data berhasil dihapus']);
    }

    public function updateStatus(Request $request)
    {
        $produk = Product::find($request->id);
        $produk->status = $request->status;
        $produk->save();
        return response()->json($produk);
    }
}

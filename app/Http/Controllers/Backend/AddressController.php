<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\City;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Address::where('user_id', auth()->user()->id)->get();

        return view('backend.address.index', compact('addresses'));
    }

    public function create()
    {
        $provinces = [];
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'key' => env('RAJAONGKIR_API_KEY'),

        ])->get('https://rajaongkir.komerce.id/api/v1/destination/province');

        if ($response->successful()) {
            $provinces = $response->json()['data'] ?? [];
        }

        return view('backend.address.add', compact('provinces'));
    }

    public function store(Request $request)
    {
        $validated = Validator::make(
            $request->all(),
            [
                'name' => 'required|string',
                'telephone' => 'required|string|min:11',
                'province' => 'required|string',
                'district' => 'required|string',
                'subdistrict' => 'required|string',
                'street' => 'required|string',
                'detail_address' => 'required|string',
            ],
            [
                'name.required' => 'Silakan isi nama terlebih dahulu.',
                'name.string' => 'Nama harus berupa teks.',
                'telephone.required' => 'Silakan isi no telepon terlebih dahulu.',
                'telephone.string' => 'Nomor telepon harus berupa teks.',
                'telephone.min' => 'Nomor telepon harus memiliki minimal :min karakter.',
                'province.required' => 'Silakan pilih provinsi terlebih dahulu.',
                'province.string' => 'Provinsi harus berupa teks.',
                'district.required' => 'Silakan pilih kota / kabupaten terlebih dahulu.',
                'district.string' => 'Kota harus berupa teks.',
                'subdistrict.required' => 'Silakan pilih kecamatan terlebih dahulu.',
                'subdistrict.string' => 'Kecamatan harus berupa teks.',
                'street.required' => 'Silakan isi jalan terlebih dahulu.',
                'street.string' => 'Jalan harus berupa teks.',
                'detail_address.required' => 'Silakan isi detail alamat terlebih dahulu.',
                'detail_address.string' => 'Detail alamat harus berupa teks.',
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            $address = new Address();
            $address->name = $request->name;
            $address->telephone = $request->telephone;
            $address->province_id = $request->province;
            $address->district_id = $request->district;
            $address->subdistrict_id = $request->subdistrict;
            $address->street = $request->street;
            $address->detail_address = $request->detail_address;
            $address->user_id = auth()->user()->id;

            $existingDefaultAddress = Address::where('default_address', 0)
                ->where('user_id', auth()->user()->id)
                ->first();

            if (!$existingDefaultAddress) {
                $address->default_address = 0;
            } else {
                if ($request->has('default_address') && $request->default_address == '0') {
                    $address->default_address = 0;
                    $existingDefaultAddress->default_address = 1;
                    $existingDefaultAddress->save();
                } else {
                    $address->default_address = 1;
                }
            }

            $address->save();

            return response()->json(['message' => 'Data berhasil disimpan']);
        }
    }

    public function edit($id)
    {
        $address = Address::find($id);
        $provinces = [];
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'key' => env('RAJAONGKIR_API_KEY'),
        ])->get('https://rajaongkir.komerce.id/api/v1/destination/province');

        if ($response->successful()) {
            $provinces = $response->json()['data'] ?? [];
        }
        
        return view('backend.address.edit', compact('provinces', 'address'));
    }

    public function update(Request $request)
    {

        $validated = Validator::make(
            $request->all(),
            [
                'name' => 'required|string',
                'telephone' => 'required|string|min:11',
                'province' => 'required|string',
                'district' => 'required|string',
                'subdistrict' => 'required|string',
                'street' => 'required|string',
                'detail_address' => 'required|string',
            ],
            [
                'name.required' => 'Silakan isi nama terlebih dahulu.',
                'name.string' => 'Nama harus berupa teks.',
                'telephone.required' => 'Silakan isi no telepon terlebih dahulu.',
                'telephone.string' => 'Nomor telepon harus berupa teks.',
                'telephone.min' => 'Nomor telepon harus memiliki minimal :min karakter.',
                'province.required' => 'Silakan pilih provinsi terlebih dahulu.',
                'province.string' => 'Provinsi harus berupa teks.',
                'district.required' => 'Silakan pilih kota / kabupaten terlebih dahulu.',
                'district.string' => 'Kota harus berupa teks.',
                'subdistrict.required' => 'Silakan pilih kecamatan terlebih dahulu.',
                'subdistrict.string' => 'Kecamatan harus berupa teks.',
                'street.required' => 'Silakan isi jalan terlebih dahulu.',
                'street.string' => 'Jalan harus berupa teks.',
                'detail_address.required' => 'Silakan isi detail alamat terlebih dahulu.',
                'detail_address.string' => 'Detail alamat harus berupa teks.',
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            $id = $request->id;
            $address = Address::find($id);
            $address->name = $request->name;
            $address->telephone = $request->telephone;
            $address->province_id = $request->province;
            $address->district_id = $request->district;
            $address->subdistrict_id = $request->subdistrict;
            $address->street = $request->street;
            $address->detail_address = $request->detail_address;
            $existingDefaultAddress = Address::where('default_address', 0)->where('user_id', auth()->user()->id)->first();

            if ($request->default_address == '0') {
                $address->default_address = 0;
                if ($existingDefaultAddress) {
                    $existingDefaultAddress->default_address = 1;
                    $existingDefaultAddress->save();
                }
            } else {
                $address->default_address = 1;
            }
            $address->user_id = auth()->user()->id;

            $address->save();

            return response()->json(['message' => 'Data berhasil disimpan']);
        }
    }


    public function destroy(Request $request)
    {
        $address = Address::where('id', $request->id)->delete();
        return Response()->json(['address' => $address, 'message' => 'Data berhasil dihapus!']);
    }

    public function getDistrict(Request $request)
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'key' => env('RAJAONGKIR_API_KEY'),

        ])->get("https://rajaongkir.komerce.id/api/v1/destination/city/{$request->id_province}");

        if ($response->successful()) {
            return $response->json()['data'] ?? [];
        }
    }

    public function getSubdistrict(Request $request)
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'key' => env('RAJAONGKIR_API_KEY'),

        ])->get("https://rajaongkir.komerce.id/api/v1/destination/district/{$request->id_district}");

        if ($response->successful()) {
            return $response->json()['data'] ?? [];
        }
    }
}

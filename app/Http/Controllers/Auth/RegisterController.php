<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\City;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'telephone' => 'required|max:15|unique:users,telephone',
            'province' => 'required',
            'district' => 'required',
            'subdistrict' => 'required',
            'street' => 'required',
            'detail_address' => 'required',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ], [
            'first_name.required' => 'Silakan isi nama depan terlebih dahulu!',
            'email.required' => 'Silakan isi email terlebih dahulu!',
            'email.email' => 'Format email tidak valid!',
            'email.unique' => 'Email sudah terdaftar!',
            'telephone.required' => 'Silakan isi nomor telepon terlebih dahulu!',
            'telephone.numeric' => 'Nomor telepon harus berupa angka!',
            'telephone.unique' => 'Nomor telepon sudah terdaftar!',
            'telephone.max' => 'Nomor telepon maksimal 15 karakter!',
            'password.required' => 'Silakan isi password terlebih dahulu!',
            'password.min' => 'Password minimal 8 karakter!',
            'password.confirmed' => 'Konfirmasi password tidak sama!',
            'password_confirmation.required' => 'Silakan isi konfirmasi password terlebih dahulu!',
            'province.required' => 'Silakan pilih provinsi terlebih dahulu!',
            'district.required' => 'Silakan pilih kota / kabupaten terlebih dahulu!',
            'subdistrict.required' => 'Silakan pilih kecamatan terlebih dahulu!',
            'street.required' => 'Silakan isi jalan terlebih dahulu!',
            'detail_address.required' => 'Silakan isi detail alamat terlebih dahulu!',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'password' => Hash::make($request->password),
            'type' => 2,
        ]);


        Address::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'telephone' => $request->telephone,
            'province_id' => $request->province,
            'district_id' => $request->district,
            'subdistrict_id' => $request->subdistrict,
            'street' => $request->street,
            'detail_address' => $request->detail_address,
            'default_address' => 0,
            'user_id' => $user->id
        ]);

        return response()->json($user);
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

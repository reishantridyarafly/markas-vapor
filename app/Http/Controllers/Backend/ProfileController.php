<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        return view('backend.profile.index');
    }


    public function settingsProfile(Request $request)
    {
        $id = auth()->user()->id;
        $validated = Validator::make(
            $request->all(),
            [
                'first_name' => 'required|string',
                'email' => 'required|string|unique:users,email,' . $id,
                'photo' => 'image|mimes:jpg,png,jpeg,webp,svg|file|max:5120',
                'telephone' => 'required|min:11|unique:users,telephone,' . $id,
            ],
            [
                'first_name.required' => 'Silakan isi nama depan terlebih dahulu.',
                'first_name.string' => 'Nama depan harus berupa teks.',
                'email.required' => 'Silakan isi email terlebih dahulu.',
                'email.string' => 'Email harus berupa teks.',
                'email.unique' => 'Email telah digunakan.',
                'photo.image' => 'File harus berupa gambar.',
                'photo.mimes' => 'Ekstensi file harus berupa: jpg, png, jpeg, webp, atau svg.',
                'photo.file' => 'File harus berupa gambar.',
                'photo.max' => 'Ukuran file tidak boleh lebih dari 5 MB.',
                'telephone.required' => 'Silakan isi no telepon terlbih dahulu.',
                'telephone.min' => 'Nomor telepon harus memiliki minimal :min karakter.',
                'telephone.max' => 'Nomor telepon tidak boleh memiliki lebih dari :max karakter.',
                'telephone.unique' => 'Nomor telepon telah digunakan.',
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                if ($file->isValid()) {
                    $randomFileName = uniqid() . '.' . $file->getClientOriginalExtension();
                    $request->file('photo')->storeAs('avatar/', $randomFileName, 'public');

                    $user = User::findOrFail($id);

                    if (Storage::exists('public/avatar/' . $user->avatar)) {
                        Storage::delete('public/avatar/' . $user->avatar);
                    }

                    $user->first_name = $request->first_name;
                    $user->last_name = $request->last_name;
                    $user->email = $request->email;
                    $user->telephone = $request->telephone;
                    $user->avatar = $randomFileName;
                    $user->bio = $request->bio;
                    $user->gender = $request->gender;
                    $user->birth_date = $request->birth_date;
                    $user->save();

                    return response()->json(['success' => 'Data berhasil disimpan']);
                }
            } else {
                $user = User::findOrFail($id);

                $user->first_name = $request->first_name;
                $user->last_name = $request->last_name;
                $user->email = $request->email;
                $user->telephone = $request->telephone;
                $user->bio = $request->bio;
                $user->gender = $request->gender;
                $user->birth_date = $request->birth_date;
                $user->save();

                return response()->json(['success' => 'Data berhasil disimpan']);
            }
        }
    }

    public function deletePhoto()
    {
        $id = auth()->user()->id;
        $user = User::findOrFail($id);

        if (!empty($user->avatar)) {
            if (Storage::exists('public/avatar/' . $user->avatar)) {
                Storage::delete('public/avatar/' . $user->avatar);
            }

            $user->avatar = null;
            $user->save();

            return response()->json(['success' => "Foto berhasil dihapus.", 'name' => $user->name]);
        } else {
            return response()->json(['success' => false, 'error' => 'Foto tidak tersedia.']);
        }
    }

    public function changePassword(Request $request)
    {
        $validated = Validator::make(
            $request->all(),
            [
                'old_password' => 'required',
                'password' => 'required|min:8|confirmed',
                'password_confirmation' => 'required',
            ],
            [
                'old_password.required' => 'Silakan isi password lama terlebih dahulu.',
                'password.required' => 'Silakan isi password baru terlebih dahulu.',
                'password.min' => 'Password harus terdiri dari minimal :min karakter.',
                'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
                'password_confirmation.required' => 'Silakan isi konfirmasi password terlebih dahulu.',
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            if (!Hash::check($request->old_password, auth()->user()->password)) {
                return response()->json(['error_password' => 'Password lama salah!']);
            } else {
                User::whereId(auth()->user()->id)->update([
                    'password' => Hash::make($request->password)
                ]);
                return response()->json(['success' => 'Password berhasil di simpan']);
            }
        }
    }

    public function deleteAccount(Request $request)
    {
        $validated = Validator::make(
            $request->all(),
            [
                'delete_password' => 'required|min:8',
            ],
            [
                'delete_password.required' => 'Silakan isi password terlebih dahulu.',
                'delete_password.min' => 'Password harus terdiri dari minimal :min karakter.',
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            $user = auth()->user();

            if (!Hash::check($request->delete_password, $user->password)) {
                return response()->json(['errors' => ['delete_password' => 'Kata sandi salah. Silakan coba lagi.']]);
            } else {
                User::whereId(auth()->user()->id)->update([
                    'active_status' => '1'
                ]);

                return response()->json(['success' => true]);
            }
        }
    }
}

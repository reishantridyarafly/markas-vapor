<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::where('id', '!=', auth()->user()->id)->where('type', '=', '1')->orderBy('first_name', 'asc')->get();
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    $avatarUrl = asset('storage/users-avatar/' . auth()->user()->avatar);

                    return '
                        <a href="customers-view.html" class="hstack gap-3">
                            <div class="avatar-image avatar-md">
                                <img src="' . $avatarUrl . '" alt="" class="img-fluid">
                            </div>
                            <div>
                                <span class="text-truncate-1-line">' . htmlspecialchars($data->first_name . ' ' . $data->last_name, ENT_QUOTES, 'UTF-8') . '</span>
                            </div>
                        </a>';
                })
                ->addColumn('status', function ($data) {
                    $checked = $data->active_status == 0 ? 'checked' : '';
                    return '<div class="form-check form-switch">
                                <input class="form-check-input status-toggle" type="checkbox" role="switch" data-id="' . $data->id . '" ' . $checked . '>
                                <label class="form-check-label" for="status">' . ($data->active_status == 0 ? 'Aktif' : 'Tidak Aktif') . '</label>
                            </div>';
                })
                ->addColumn('action', function ($data) {
                    return '
                        <div class="hstack gap-2 justify-content-end">
                            <div class="dropdown">
                                <a href="javascript:void(0)" class="avatar-text avatar-md" data-bs-toggle="dropdown" data-bs-offset="0,21">
                                    <i class="feather feather-more-horizontal"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <button class="dropdown-item" id="btnEdit" data-id="' . $data->id . '">
                                            <i class="feather feather-edit-3 me-3"></i>
                                            <span>Edit</span>
                                        </button>
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
                ->rawColumns(['name', 'status', 'action'])
                ->make(true);
        }
        return view('backend.users.index');
    }

    public function store(Request $request)
    {
        $id = $request->id;
        $validated = Validator::make(
            $request->all(),
            [
                'first_name' => 'required',
                'email' => 'required|unique:users,email,' . $id,
                'telephone' => 'required|min:11|max:13|unique:users,telephone,' . $id,
            ],
            [
                'first_name.required' => 'Silakan isi nama depan terlebih dahulu',
                'email.required' => 'Silakan isi email terlebih dahulu',
                'email.unique' => 'Email sudah digunakan',
                'telephone.required' => 'Silakan isi no telepon terlebih dahulu',
                'telephone.min' => 'No telepon :min karakter',
                'telephone.max' => 'No telepon :max karakter',
                'telephone.unique' => 'No telepon sudah digunakan',
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            $userData = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'telephone' => $request->telephone,
                'type' => 1,
            ];

            if (!$id) {
                $userData['password'] = Hash::make('123456789');
            }

            $user = User::updateOrCreate(['id' => $id], $userData);

            return response()->json(['user' => $user, 'message' => 'Data berhasil disimpan.']);
        }
    }

    public function edit($id)
    {
        $data = User::find($id);
        return response()->json($data);
    }

    public function destroy(Request $request)
    {
        $user = User::find($request->id);
        if (Storage::exists('public/avatar/' . $user->avatar)) {
            Storage::delete('public/avatar/' . $user->avatar);
        }
        $user->delete();
        return Response()->json(['user' => $user, 'message' => 'Data berhasil dihapus']);
    }

    public function updateStatus(Request $request)
    {
        $user = User::find($request->id);
        $user->active_status = $request->status;
        $user->save();
        return response()->json($user);
    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AdministratorController extends Controller
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
                ->addColumn('action', function ($data) {
                    return '
                        <div class="hstack gap-2 justify-content-end">
                            <div class="dropdown">
                                <a href="javascript:void(0)" class="avatar-text avatar-md" data-bs-toggle="dropdown" data-bs-offset="0,21">
                                    <i class="feather feather-more-horizontal"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="' . route('user', $data->id) . '" class="dropdown-item" target="_blank">
                                            <i class="feather feather-mail me-3"></i>
                                            <span>Kirim Pesan</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>';
                })
                ->rawColumns(['name', 'action'])
                ->make(true);
        }
        return view('backend.administrator.index');
    }
}

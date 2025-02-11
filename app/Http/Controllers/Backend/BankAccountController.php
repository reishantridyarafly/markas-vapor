<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class BankAccountController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $bank_account = BankAccount::all();
            return DataTables::of($bank_account)
                ->addIndexColumn()
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
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.bankaccount.index');
    }

    public function store(Request $request)
    {
        $id = $request->id;
        $validated = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'bank_name' => 'required',
                'account_number' => 'required|unique:bank_accounts,account_number,' . $id,
            ],
            [
                'name.required' => 'Silakan isi nama terlebih dahulu.',
                'bank_name.required' => 'Silakan isi bank terlebih dahulu.',
                'account_number.required' => 'Silakan isi rekening bank terlebih dahulu.',
                'account_number.unique' => 'Rekening bank sudah tersedia.'
            ]
        );

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        } else {
            $catalog = BankAccount::updateOrCreate([
                'id' => $id
            ], [
                'name' => $request->name,
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
            ]);
            return response()->json(['catalog' => $catalog, 'message' => 'Data berhasil disimpan.']);
        }
    }

    public function edit($id)
    {
        $data = BankAccount::find($id);
        return response()->json($data);
    }

    public function destroy(Request $request)
    {
        $bank_account = BankAccount::where('id', $request->id)->delete();
        return Response()->json(['bank_account' => $bank_account, 'message' => 'Data berhasil dihapus']);
    }
}

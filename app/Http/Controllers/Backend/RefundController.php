<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Refund;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class RefundController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if (auth()->user()->type == 'Pelanggan') {
                $refund = Refund::with('transaction')->where('user_id', auth()->user()->id)->orderBy('created_at', 'asc')->get();
            } else {
                $refund = Refund::with('transaction')->orderBy('created_at', 'asc')->get();
            }
            return DataTables::of($refund)
                ->addIndexColumn()
                ->addColumn('invoice', function ($data) {
                    return $data->transaction->code;
                })
                ->addColumn('name', function ($data) {
                    $name = $data->user->first_name . ' ' . $data->user->last_name;
                    return $name;
                })
                ->addColumn('status', function ($data) {
                    $status = '';
                    if ($data->status == 'pending') {
                        $status = '<span class="fw-bold text-warning">Pending</span>';
                    } elseif ($data->status == 'process') {
                        $status = '<span class="fw-bold text-warning">Proses</span>';
                    } elseif ($data->status == 'completed') {
                        $status = '<span class="fw-bold text-success">Selesai</span>';
                    } else {
                        $status = '<span class="fw-bold text-danger">Ditolak</span>';
                    }
                    return $status;
                })
                ->addColumn('action', function ($data) {
                    $action = '';
                    if (auth()->user()->type == 'Administrator') {
                        $action = '<li>
                                        <a href="' . route('refund.detail', $data->id) . '" class="dropdown-item">
                                            <i class="feather feather-eye me-3"></i>
                                            <span>Lihat</span>
                                        </a>
                                    <li>
                                        <button class="dropdown-item" id="btnDelete" data-id="' . $data->id . '">
                                            <i class="feather feather-trash-2 me-3"></i>
                                            <span>Hapus</span>
                                        </button>
                                    </li>';
                    } else {
                        $action = '<li>
                        <a href="' . route('refund.detail', $data->id) . '" class="dropdown-item">
                            <i class="feather feather-eye me-3"></i>
                            <span>Lihat</span>
                        </a>';
                    }
                    return '
                        <div class="hstack gap-2 justify-content-end">
                            <div class="dropdown">
                                <a href="javascript:void(0)" class="avatar-text avatar-md" data-bs-toggle="dropdown" data-bs-offset="0,21">
                                    <i class="feather feather-more-horizontal"></i>
                                </a>
                                <ul class="dropdown-menu">
                                ' . $action . '
                                </ul>
                            </div>
                        </div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('backend.refund.index');
    }

    public function detail($id)
    {
        $refund = Refund::with('transaction', 'user')->find($id);
        return view('backend.refund.detail', compact('refund'));
    }

    public function failed(Request $request)
    {
        $refund = Refund::find($request->id);
        $refund->status = 'failed';
        $refund->save();

        $transaction = Transaction::find($refund->transaction_id);
        $transaction->status = 'completed';
        $transaction->save();

        return response()->json(['message' => 'Pengembalian ditolak!']);
    }

    public function process(Request $request)
    {
        $refund = Refund::find($request->id);
        $refund->status = 'process';
        $refund->processed_date = now();
        $refund->save();

        return response()->json(['message' => 'Pengembalian diproses!']);
    }

    public function completed(Request $request)
    {
        $refund = Refund::find($request->id);
        $refund->status = 'completed';
        $refund->save();

        return response()->json(['message' => 'Pengembalian selesai diproses!']);
    }

    public function destroy(Request $request)
    {
        $refund = Refund::findOrFail($request->id);

        $fileNames = $refund->refundProofs()->pluck('file_refund');

        foreach ($fileNames as $fileName) {
            if (Storage::disk('public')->exists('uploads/refunds/' . $fileName)) {
                Storage::disk('public')->delete('uploads/refunds/' . $fileName);
            }
        }

        $refund->refundProofs()->delete();
        $refund->delete();

        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}

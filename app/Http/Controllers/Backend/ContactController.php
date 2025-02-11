<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $contact = Contact::orderBy('created_at', 'desc')->get();
            return DataTables::of($contact)
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
                                        <button class="dropdown-item" id="btnDetail" data-id="' . $data->id . '">
                                            <i class="feather feather-eye me-3"></i>
                                            <span>Detail</span>
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
        return view('backend.contact.index');
    }

    public function detail($id)
    {
        $data = Contact::find($id);
        return response()->json($data);
    }

    public function destroy(Request $request)
    {
        $contact = Contact::where('id', $request->id)->delete();
        return Response()->json(['contact' => $contact, 'message' => 'Data berhasil dihapus']);
    }
}

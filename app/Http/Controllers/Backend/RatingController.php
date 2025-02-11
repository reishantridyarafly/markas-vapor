<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RatingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $rating = Rating::with('user', 'product')->orderBy('created_at', 'desc')->get();
            return DataTables::of($rating)
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    $name = $data->user->first_name . ' ' . $data->user->last_name;
                    return $name;
                })
                ->addColumn('product', function ($data) {
                    return $data->product->name;
                })
                ->addColumn('action', function ($data) {
                    return '<div class="hstack gap-2 justify-content-end" id="btnDelete" data-id="' . $data->id . '">
                    <button class="avatar-text avatar-md">
                        <i class="feather feather-trash-2"></i>
                    </button>
                </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.rating.index');
    }

    public function destroy(Request $request)
    {
        $rating = Rating::where('id', $request->id)->delete();
        return Response()->json(['rating' => $rating, 'message' => 'Data berhasil dihapus']);
    }
}

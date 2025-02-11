<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $rating = new Rating();
        $rating->user_id = auth()->user()->id;
        $rating->product_id = $request->product_id;
        $rating->rating = $request->rating;
        $rating->comment = $request->comment;
        $rating->save();

        return response()->json(['message' => 'Data berhasil dikirim.']);
    }
}

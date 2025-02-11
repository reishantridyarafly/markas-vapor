<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        $testimoni = Rating::with('user')->where('rating', '>', '3.50')->orderBy('created_at', 'desc')->get();
        return view('frontend.about.index', compact('testimoni'));
    }
}

<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $assets = Asset::all();

        return view('landing', [
            'assets' => $assets
        ]);
    }
}

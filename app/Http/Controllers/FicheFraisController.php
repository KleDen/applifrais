<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\Controllers\View;
// use Illuminate\View\View;

class FicheFraisController extends Controller
{
    public function create(Request $request): View
    {
        return view('fiches.create', [
            'user' => $request->user(),
        ]);
    }
}

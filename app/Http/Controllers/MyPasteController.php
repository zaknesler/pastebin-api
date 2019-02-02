<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\PasteResource;

class MyPasteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $pastes = $request->user()
            ->pastes()
            ->notExpired()
            ->latest()
            ->get();

        return PasteResource::collection($pastes);
    }
}

<?php

namespace App\Http\Controllers;

use App\Paste;
use Illuminate\Http\Request;
use App\Http\Resources\PasteResource;

class PasteController extends Controller
{
    /**
     * Store a paste in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'body' => 'required',
            'language' => 'nullable|in:' . implode(',', config('pastebin.languages')),
            'visibility' => 'required|in:public,private,unlisted',
            'expires_at' => 'date|after:now',
        ]);

        $paste = Paste::create($request->all());

        if ($user = $request->user()) {
            $paste->user()->associate($user);
        }

        return new PasteResource($paste);
    }
}

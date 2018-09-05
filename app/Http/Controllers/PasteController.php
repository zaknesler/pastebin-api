<?php

namespace App\Http\Controllers;

use App\Paste;
use App\Errors\NoAccess;
use App\Errors\PasteExpired;
use Illuminate\Http\Request;
use App\Errors\MustBeAuthenticated;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\PasteResource;

class PasteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    function __construct()
    {
        $this->middleware('auth')
            ->only('update');
    }

    /**
     * Display a listing of all pastes.
     *
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function index()
    {
        $pastes = Paste::public()->paginate(10);

        return PasteResource::collection($pastes);
    }

    /**
     * Store a paste in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:8',
            'body' => 'required|max:256000',
            'visibility' => 'required|in:public,private,unlisted',
            'language' => 'nullable|in:' . implode(',', config('pastebin.languages')),
            'expires_at' => 'nullable|date|after:now',
        ]);

        if ($request->visibility == 'private' && !auth()->check()) {
            return new ErrorResource(new MustBeAuthenticated);
        }

        $paste = Paste::create($request->all());

        if ($user = $request->user()) {
            $paste->user()->associate($user);
        }

        return new PasteResource($paste);
    }

    /**
     * Display a paste by its slug.
     *
     * @param  \App\Paste  $paste
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function show(Paste $paste, Request $request)
    {
        if ($paste->isPrivate()
            && !$paste->isOwnedBy($request->user())) {
            return new ErrorResource(new NoAccess);
        }

        if ($paste->hasExpired()) {
            return new ErrorResource(new PasteExpired);
        }

        return new PasteResource($paste);
    }

    /**
     * Update the specified paste.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Paste
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Paste $paste)
    {
        if (!$paste->isOwnedBy($request->user())) {
            return new ErrorResource(new NoAccess);
        }

        $validatedData = $request->validate([
            'name' => 'sometimes|required|min:8',
            'body' => 'sometimes|required|max:256000',
            'visibility' => 'sometimes|required|in:public,private,unlisted',
            'language' => 'sometimes|nullable|in:' . implode(',', config('pastebin.languages')),
            'expires_at' => 'sometimes|nullable|date|after:now',
        ]);

        $paste->update($validatedData);

        return new PasteResource($paste);
    }
}

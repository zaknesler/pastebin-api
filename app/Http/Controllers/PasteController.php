<?php

namespace App\Http\Controllers;

use App\Paste;
use Illuminate\Http\Request;
use App\Http\Resources\PasteResource;
use App\Http\Responses\CustomResponse;
use App\Http\Responses\Errors\NoAccess;
use App\Http\Responses\Errors\PasteExpired;
use App\Http\Responses\Errors\MustBeAuthenticated;

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
            ->only(['update', 'destroy']);
    }

    /**
     * Display a listing of all pastes.
     *
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function index()
    {
        $pastes = Paste::latest()->public()->paginate(10);

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
            'language' => 'nullable|in:' . implode(',', array_keys(config('pastebin.languages'))),
            'expires_at' => 'nullable|date|after:now',
        ]);

        if ($request->visibility == 'private' && !auth()->check()) {
            return apiResponse(new MustBeAuthenticated);
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
        if ($paste->isPrivate() && !$paste->isOwnedBy($request->user())) {
            return apiResponse(new NoAccess);
        }

        if ($paste->hasExpired()) {
            return apiResponse(new PasteExpired);
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
            return apiResponse(new NoAccess);
        }

        $validatedData = $request->validate([
            'name' => 'sometimes|required|min:8',
            'body' => 'sometimes|required|max:256000',
            'visibility' => 'sometimes|required|in:public,private,unlisted',
            'language' => 'sometimes|nullable|in:' . implode(',', array_keys(config('pastebin.languages'))),
            'expires_at' => 'sometimes|nullable|date|after:now',
        ]);

        $paste->update($validatedData);

        return new PasteResource($paste);
    }

    /**
     * Delete a specified paste.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Paste  $paste
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Paste $paste)
    {
        if (!$paste->isOwnedBy($request->user())) {
            return apiResponse(new NoAccess);
        }

        $paste->delete();

        return apiResponse('Paste has been deleted.', 202);
    }
}

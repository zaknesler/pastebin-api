<?php

namespace App\Http\Controllers;

use App\Paste;
use Illuminate\Http\Request;
use App\Errors\MustBeAuthenticated;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\PasteResource;

class PasteController extends Controller
{
    /**
     * Display a listing of all pastes.
     *
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function index()
    {
        $pastes = Paste::paginate(10);

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
            'name' => 'required',
            'body' => 'required',
            'visibility' => 'required|in:public,private,unlisted',
            'language' => 'nullable|in:' . implode(',', config('pastebin.languages')),
            'expires_at' => 'nullable|date|after:now',
        ]);

        $paste = Paste::create($request->all());

        if ($request->visibility == 'private' && !auth()->check()) {
            return new ErrorResource(new MustBeAuthenticated);
        }

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
            && !$paste->isOwnedBy(request()->user())) {
            abort(404);
        }

        if (optional($paste->expires_at)->lte(now())) {
            abort(404);
        }

        return new PasteResource($paste);
    }
}

<?php

namespace App\Http\Resources;

use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PasteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'body' => $this->body,
            'visibility' => $this->visibility,
            'language' => $this->language,
            'expires_at' => $this->expires_at,
            'user' => new UserResource($this->user),
        ];
    }
}

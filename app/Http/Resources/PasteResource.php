<?php

namespace App\Http\Resources;

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
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'body' => $this->body,
            'visibility' => $this->visibility,
            'language' => $this->language,
            'expires_at' => $this->expires_at,
            'user' => new PublicUserResource($this->user),
        ];
    }
}

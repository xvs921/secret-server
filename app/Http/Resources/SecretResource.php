<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SecretResource extends JsonResource
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
            "hash" => $this->hash.'\n',
            "secretText" => $this->secretText.'\n',
            "createdAt" => $this->created_at.'\n',
            "expiresAt" => $this->expires_at.'\n',
            "remainingViews" => $this->remainingViews.'\n'
        ];
    }
}

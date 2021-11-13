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
            'hash'           => $this->hash,
            'secretText'     => $this->secretText,
            'createdAt'      => $this->created_at,
            'expiresAt'      => $this->expires_at,
            'remainingViews' => $this->remainingViews,
        ];
    }
}

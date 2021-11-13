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
            'hash'           => $request->hash,
            'secretText'     => $request->secretText,
            'createdAt'      => $request->created_at,
            'expiresAt'      => $request->expires_at,
            'remainingViews' => $request->remainingViews,
        ];
    }
}

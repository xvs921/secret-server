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
            "hash" => $secretObject->hash,
            "secretText" => $secretObject->secretText,
            "createdAt" => $secretObject->created_at,
            "expiresAt" => $secretObject->expires_at,
            "remainingViews" => $secretObject->remainingViews
        ];
    }
}

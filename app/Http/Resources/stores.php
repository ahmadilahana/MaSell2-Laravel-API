<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class StoreResources extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "storeId" => $this->storeId,
            "sellerAppId" => $this->sellerAppId,
            "name" => $this->name,
            "logo" => $this->logo,
            "sellerId" => $this->sellerId,
            "platform" => Platforms::where('platformId')->get(),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }
}

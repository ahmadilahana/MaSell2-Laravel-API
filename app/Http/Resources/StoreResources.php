<?php

namespace App\Http\Resources;

use App\Models\Platforms;
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
        return $this->collection->each(function ($item, $key)
        {
            $platform = Platforms::where('platformId', $item->platformId)->get()->first();
            
            return $item['platform'] = $platform;
        });

        
    }
}

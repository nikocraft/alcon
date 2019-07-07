<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\SettingResource;
use App\Http\Resources\TaxonomyResource;

class ContentTypeResource extends JsonResource
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
            'name_singular' => $this->name_singular,
            'settings' => SettingResource::collection($this->whenLoaded('settings')),
            'taxonomies' => TaxonomyResource::collection($this->whenLoaded('taxonomies')),
            'type' => $this->type,
            'slug' => $this->slug,
            'front_slug' => $this->front_slug,
        ];
    }
}

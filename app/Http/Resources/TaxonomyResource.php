<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\TermResource;

class TaxonomyResource extends JsonResource
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
            'slug' => $this->slug,
            'order' => $this->order,
            'terms' => TermResource::collection($this->whenLoaded('terms')),
        ];
    }
}

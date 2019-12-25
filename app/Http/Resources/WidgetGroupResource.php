<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WidgetGroupResource extends JsonResource
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
            'title' => $this->title,
            'layout' => $this->layout,
            'location' => $this->location,
            'filter_mode' => $this->filter_mode,
            'filter_data' => $this->filter_data,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'widgets' => BlockResource::collection($this->whenLoaded('widgets')),
        ];
    }
}

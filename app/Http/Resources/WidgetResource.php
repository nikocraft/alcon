<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WidgetResource extends JsonResource
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
            'theme_area' => $this->theme_area,
            'filter_mode' => $this->filter_mode,
            'filter_data' => $this->filter_data,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'settings' => $this->settings,
            'blocks' => BlockResource::collection($this->whenLoaded('blocks')),
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ThemeSettingsResource extends JsonResource
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
            'theme_id' => $this->theme_id,
            'parent_id' => $this->parent_id,
            'section' => optional($this->parent)->key,
            'label' => $this->label,
            'key' => $this->key,
            'value' => $this->value,
            'type' => $this->type,
            'meta' => $this->meta,
            'settings' => ThemeSettingsResource::collection($this->settings)
        ];
    }
}

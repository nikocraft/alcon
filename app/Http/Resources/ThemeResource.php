<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ThemeResource extends JsonResource
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
            'namespace' => $this->namespace,
            'name' => $this->name,
            'author' => $this->author,
            'version' => $this->version,
            'description' => $this->description,
            'url' => $this->url,
            'settings' => ThemeSettingsResource::collection($this->sections),
        ];
    }
}

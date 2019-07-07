<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TemplateBlockResource extends JsonResource
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
            'unique_id' => $this->unique_id,
            'order' => $this->order,
            'parent_id' => $this->parent_id,
            'settings' => $this->settings,
            'template_id' => $this->template_id,
            'type' => $this->type,
            'unique_id,' => $this->unique_id,
            'content' => $this->content,
        ];
    }
}

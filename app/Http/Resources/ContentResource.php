<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\SettingResource;

class ContentResource extends JsonResource
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
            'slug' => $this->slug,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'published_at' => (string) $this->published_at,
            'status' => $this->status,
            'author' => $this->author,
            'layout' => $this->layout,
            'featuredimage' => $this->featuredimage,
            'terms' => $this->terms,
            'seo' => $this->seo,
            'css' => $this->css,
            'js' => $this->js,
            'settings' => $this->settings,
            'blocks' => $this->processBlocks($this->blocks)
        ];
    }
}

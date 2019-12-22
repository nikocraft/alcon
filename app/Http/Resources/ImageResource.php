<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\TagResource;

class ImageResource extends JsonResource
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
            'filename' => $this->filename,
            'title' => $this->title,
            'alt' => $this->alt,
            'caption' => $this->caption,
            'description' => $this->description,
            'slug' => $this->slug,
            'path' => $this->path,
            'extension' => $this->extension,
            'large' => $this->large,
            'medium' => $this->medium,
            'original' => $this->original,
            'thumb' => $this->thumb,
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'author' => $this->whenLoaded('author'),
        ];
    }
}

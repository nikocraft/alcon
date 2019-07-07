<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'body' => $this->body,
            'name' => $this->name,
            'parent_id' => $this->parent_id,
            'author' => new CommentAuthorResource($this->author),
            'created_at' => (string) $this->created_at,
        ];
    }
}

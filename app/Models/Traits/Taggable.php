<?php
namespace App\Models\Traits;

use App\Models\Core\Base\Tag;

trait Taggable {

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function getTag($id)
    {
        return $this->tags()->where('id', $id)->first();
    }

    public function tagged($id)
    {
        return $this->tags()->find('id', $id)->exists();
    }

    public function tag($tags)
    {
        $this->tags()->detach();

        foreach($tags as $tagObj) {
            if (array_key_exists('id', $tagObj)) {
                // if($this->tagged($tagObj["id"]))
                    $tag= Tag::find($tagObj["id"]);
            } else {

                $tag = Tag::firstOrCreate([
                    'title' => strtolower($tagObj["title"]),
                    'slug' => $this->slugify($tagObj["title"])
                ]);
            }
            $this->tags()->attach($tag);
        }
    }
}

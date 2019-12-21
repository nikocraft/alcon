<?php

namespace App\Http\Controllers\Backend\Spa\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Core\Content\ContentType;
use App\Models\Core\Taxonomies\Taxonomy;
use App\Http\Resources\ContentTypeResource;

class UrlsController extends Controller
{
    public function index(Request $request)
    {
        return ContentTypeResource::collection(ContentType::with('taxonomies')->whereType(2)->get());
    }

    public function store(Request $request)
    {
        foreach ($request->content as $key => $item) {
            ContentType::find($item['id'])
                ->update(['front_slug' => $item['front_slug']]);
        }
        foreach ($request->taxonomies as $key => $item) {
            Taxonomy::find($item['id'])
                ->update(['slug' => $item['slug']]);
        }

        return response()->json(null, 200);
    }
}

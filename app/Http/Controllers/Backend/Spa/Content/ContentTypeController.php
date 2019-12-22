<?php

namespace App\Http\Controllers\Backend\Spa\Content;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Core\Content\ContentType;
use App\Http\Resources\ContentTypeResource;

class ContentTypeController extends Controller
{
    public function list(Request $request)
    {
        $contentType = ContentType::all();
        return ContentTypeResource::collection($contentType);
    }

    public function show($contentTypeId) {
        $contentType = ContentType::whereId($contentTypeId)->first();
        $settings = get_website_setting('atlas.content.indexPage');

        return (new ContentTypeResource($contentType))
            ->additional(compact('settings'));
    }
}

<?php

namespace App\Http\Controllers\Backend\Spa\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Core\Content\ContentType;
use App\Models\Core\Content\ContentTypeBlock;
use App\Models\Core\Taxonomies\Taxonomy;
use App\Models\Core\Settings\AdminMenu;

use App\Http\Resources\ContentTypeResource;

class ContentTypeController extends Controller
{
    public function index(Request $request)
    {
        $contentType = ContentType::latest()->with(['taxonomies'])->paginate(10);
        return ContentTypeResource::collection($contentType);
    }

    public function store(Request $request)
    {
        // $contentType = ContentType::where('id', $request->id)->first();
        if ($request->id > 0)
            $contentType = ContentType::where('id', $request->id)->first();
        else {
            $contentType = new ContentType();
        }

        $typeData = $request->typeData;
        if (!$typeData['slug'])
            $typeData['slug'] = lcfirst($typeData['name_singular']);

        if (!isset($typeData['front_slug']) || !$typeData['front_slug'])
            $typeData['front_slug'] = $typeData['slug'];

        $typeData['type'] = 2;

        $contentType->fill($typeData);
        $contentType->save();

        // Save Content Type Settings
        // foreach ($request->typeSettings as $key => $setting) {
        //     $contentType->setSetting($key, $setting['value'], $setting['type']);
        // }

        $taxonomies = $request->taxonomies;
        for ($i=0; $i < count($taxonomies); $i++) {
            $t = $taxonomies[$i];

            $data = $t['data'];
            $settings = $t['settings'];

            $taxonomy = Taxonomy::createOrUpdate($contentType->id, $data, $settings);
        }

        foreach ($request->removedTaxonomies as $id) {
            Taxonomy::destroy($id);
        }

        $contentType = ContentType::where('id', $contentType->id)->first();
        $contentType->taxonomies = $contentType->taxonomies()->orderBy('order')->get();

        foreach ($contentType->taxonomies as $key => $taxonomy) {
            $taxonomy->settings;
        }

        if ($contentType) {
            return response()->json([
                'data' => $contentType
            ]);
        }
    }

    public function show($id)
    {
        $contentType = ContentType::where('id', $id)->with('taxonomies')->first();

        return response()->json([
            'data' => $contentType
        ]);
    }

    public function taxonomies($id)
    {
        $contentType = ContentType::where('id', $id)->first();
        $contentType->taxonomies = $contentType->taxonomies()->orderBy('order')->get();

        return response()->json([
            'taxonomies' => $contentType->taxonomies
        ]);
    }

    public function destroy(Request $request)
    {
        $contentTypeBlock = ContentTypeBlock::where('id', $request->id)->first();

        $contentTypeBlock->delete();

        return response()->json(null, 204);
    }
}

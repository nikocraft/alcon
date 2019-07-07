<?php

namespace App\Http\Controllers\Backend\Spa\Content;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Core\Content\ContentType;
use App\Models\Core\Taxonomies\Term;

use App\Http\Resources\TermResource;

class TermController extends Controller
{
    public function store(Request $request)
    {
        $termData = (object)$request->term;
        $term = Term::updateOrCreate(
            ['id' => $termData->id],
            ['name' => $termData->name, 'description' => $termData->description, 'taxonomy_id' => $termData->taxonomy_id]
        );

        $term->slug = $termData->name;
        $term->save();

        return new TermResource($term);
    }

    public function destroy(Request $request, $id)
    {
        $term = Term::where('id', $id)->first();

        if($term->delete()) {
            return response()->json(null, 204);
        } else {
            return response()->json([
                'message' => 'Could not delete.'
            ], 403);
        }
    }
}

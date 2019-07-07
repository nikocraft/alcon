<?php

namespace App\Http\Controllers\Backend\Spa\Content;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Core\Content\ContentType;
use App\Models\Core\Taxonomies\Taxonomy;
use App\Models\Core\Taxonomies\Term;

use App\Http\Resources\TaxonomyResource;
use App\Http\Resources\TermResource;

class TaxonomyController extends Controller
{
    public function show(Request $request, $contentTypeId, $taxonomyId)
    {
        $taxonomy = Taxonomy::with('terms')
            ->where('content_type_id', $contentTypeId)
            ->whereId($taxonomyId)
            ->first();

        return new TaxonomyResource($taxonomy);
    }
}

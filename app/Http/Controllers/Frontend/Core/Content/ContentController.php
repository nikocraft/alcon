<?php

namespace App\Http\Controllers\Frontend\Core\Content;

use Auth;
use Session;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Core\Content\Content;
use App\Models\Core\Content\ContentType;
use App\Models\Core\Content\ContentBlock;
use App\Models\Core\Taxonomies\Taxonomy;
use App\Models\User;

use App\Services\RenderContentService;

class ContentController extends Controller
{
    protected $renderService;
    protected $paginationType;
    protected $perPage;

    public function __construct(RenderContentService $renderService)
	{
        $this->renderService = $renderService;
        $this->paginationType = get_website_setting('website.general.paginationType', 'simple');
        $this->perPage = get_website_setting('website.general.paginationPerPage', 12);
    }

    public function frontPage(Request $request)
    {
        $frontPageType = get_website_setting('website.general.frontPageType');
        $frontPageMeta = get_website_setting('website.general.frontPageMeta');

        switch ($frontPageType) {
            case 'single-page':
                $content = Content::find($frontPageMeta);
                if($content)
                    return $this->show($content->slug, $content->type->id);
                else
                    return $this->welcome();
            break;

            case 'index-page':
                $contentType = ContentType::find($frontPageMeta);
                if($contentType)
                    return $this->index($request, $contentType->id);
                else
                    return $this->welcome();
            break;

            default:
                return $this->welcome();
            break;
        }
    }

    protected function welcome()
    {
        $pageType = "welcome";
        return view('content.template.default.welcome', compact('pageType'));
    }

    public function index(Request $request, $contentTypeId)
    {
        $contentType = ContentType::findOrFail($contentTypeId);
        $posts = Content::with(['featuredimage', 'author', 'blocks'])->where('content_type_id', $contentTypeId)->published()->latest();
        $posts = ($this->paginationType == 'simple') ? $posts->simplePaginate($this->perPage) : $posts->paginate($this->perPage);

        return $this->renderService->renderIndex($contentType, $posts);
    }

    public function show($slug, $contentTypeId)
    {
        $contentType = ContentType::findOrFail($contentTypeId);
        $content = Content::with(['featuredimage', 'type', 'author'])
            ->with('terms.taxonomies')
            ->where('slug', $slug)
            ->where('content_type_id', $contentTypeId)
            ->firstOrFail();

        $blocks = $this->hierarchicalSort( $content->blocks()->orderBy('order')->get() );

        return $this->renderService->renderSingle($content, $blocks);
    }

    public function taxonomy(Request $request, $term, $taxonomyId)
    {
        $taxonomy = Taxonomy::findOrFail($taxonomyId);
        $contentType = $taxonomy->type;
        $posts = Content::with(['featuredimage', 'author', 'terms', 'blocks'])
            ->where('content_type_id', $contentType->id)
            ->whereHas('terms', function($query) use ($term) {
                $query->where('slug', 'LIKE', "%$term%");
            })
            ->latest();

        $posts = ($this->paginationType == 'simple') ? $posts->simplePaginate($this->perPage) : $posts->paginate($this->perPage);

        return $this->renderService->renderIndex($contentType, $posts);
    }

    private function hierarchicalSort($elements, $rootEl = null)
    {
        $data = collect();
        $filteredElements = $elements->filter(function ($o) use ($rootEl) {
            return $o['parent_id'] == $rootEl;
        });
        $data = $data->merge($filteredElements);
        foreach ($filteredElements as $element) {
            $data = $data->merge( $this->hierarchicalSort($elements, $element['unique_id']) );
        }
        return $data;
    }
}

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

use App\Models\Core\Design\Widget;
use App\Models\User;

use App\Services\RenderContentService;

class ContentController extends Controller
{
    protected $renderService;

    public function __construct(RenderContentService $renderService)
	{
        $this->renderService = $renderService;
    }
    
    public function frontPage(Request $request)
    {
        $frontPageType = get_website_setting('website.frontPageType');
        $frontPageMeta = get_website_setting('website.frontPageMeta');

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
        $contentType = ContentType::with('settings')->findOrFail($contentTypeId);
        $posts = Content::with(['featuredimage', 'author', 'blocks'])->where('content_type_id', $contentTypeId)->latest()
            ->simplePaginate(5);

        $widgets = Widget::with(['blocks' => function ($query) {
            $query->with('settings')->orderBy('order', 'asc');
        }])->get();

        return $this->renderService->renderIndex($contentType, $posts, $widgets);
    }

    public function show($slug, $contentTypeId)
    {
        $contentType = ContentType::with('settings')->findOrFail($contentTypeId);
        $content = Content::with(['featuredimage', 'type', 'author'])
            ->with('terms.taxonomies')
            ->where('slug', $slug)
            ->where('content_type_id', $contentTypeId)
            ->firstOrFail();
        
        $blocks = ContentBlock::with(array('settings'=>function($query) {
            $query->select('id','key', 'value', 'type');
        }))->where('content_id', $content->id)->orderBy('parent_id')->orderBy('order')->get();

        $widgets = Widget::with(['blocks' => function ($query) {
            $query->with('settings')->orderBy('order', 'asc');
        }])->get();

        return $this->renderService->renderSingle($content, $blocks, $widgets);
    }

    public function taxonomy(Request $request, $term, $taxonomyId)
    {
        $taxonomy = Taxonomy::findOrFail($taxonomyId);
        $contentType = $taxonomy->type;
        $posts = Content::with(['featuredimage', 'author', 'terms'])
            ->where('content_type_id', $contentType->id)
            ->whereHas('terms', function($query) use ($term) {
                $query->where('slug', 'LIKE', "%$term%");
            })
            ->latest()
            ->simplePaginate(5);

        $widgets = Widget::with(['blocks' => function ($query) {
            $query->with('settings')->orderBy('order', 'asc');
        }])->get();

        return $this->renderService->renderIndex($contentType, $posts, $widgets);
    }
}
<?php

namespace App\Http\Controllers\Backend\Spa\Media;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Core\Media\Gallery;
use App\Models\Core\Media\Image;

use Auth;
use Validator;
use Carbon\Carbon;
use ImgManager;
use Session;
use App\Models\Core\Base\Tag;

use App\Http\Resources\ImageResource;
use App\Http\Resources\TagResource;

class ImageController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $filter = $request->filter;
        $page = $request->page;
        $append = array();
        $perPage = 25;

        $order = $request->order;
        $tagFilter = $request->tag;

        $images = Image::with('tags');

        switch($order) {
            case 'newer':
                $images = $images->latest();
                break;
            case 'older':
                $images = $images->oldest();
                break;
            default:
                $images = $images->latest();
                break;
        }

        if($tagFilter) {
            $images = $images->whereHas('tags', function ($query) use ($tagFilter) {
                $query->where('slug', $tagFilter);
            });
        }

        $images = $images->with('author')->paginate($perPage);
        $tags = Tag::has('images')->get();

        return ImageResource::collection($images)
            ->additional(compact('tags'));
    }

    public function show(Request $request, $id)
    {
        $image = Image::with('tags')->findOrFail($id);
        return new ImageResource($image);
    }

    protected function update(Request $request, $id)
    {
        $image = Image::findOrFail($id);
        $image->title = $request->title;
        $image->alt = $request->alt;
        $image->caption = $request->caption;
        $image->description = $request->description;
        $image->tag($request->input('tags', []));
        $image->save();

        return new ImageResource($image);
    }

    public function destroy(Request $request, $id)
    {
        $image = Image::findOrFail($id);

        if($image->delete()) {
            return response()->json(null, 204);
        } else {
            return response()->json([
                'message' => 'Could not delete.'
            ], 403);
        }
    }

    public function tags(Request $request)
    {
        return TagResource::collection(Tag::has('images')->get());
    }
}

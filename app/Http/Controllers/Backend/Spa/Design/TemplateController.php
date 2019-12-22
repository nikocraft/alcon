<?php

namespace App\Http\Controllers\Backend\Spa\Design;

use DB;
use Session;
use Auth;
use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TemplateResource;
use App\Http\Resources\TemplateBlockResource;
use App\Http\Resources\TagResource;

use App\Models\Core\Content\ContentType;
use App\Models\Core\Content\Template;
use App\Models\Core\Content\Block;
use App\Models\Core\Base\Tag;

class TemplateController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 15;

        if ($request->tags) {
            $ids = array_map('intval', explode(',', $request->tags));
            $templates = Template::whereHas('tags', function ($query) use ($ids) {
                $query->whereIn('id', $ids);
            });
        } else {
            $templates = Template::latest();
        }

        $templates = $templates->with('tags')->paginate($perPage);

        return TemplateResource::collection($templates);
    }

    public function show($id)
    {
        $tmpBlocks = TemplateBlock::where('template_id', $id)->get();

        // json decode string to json
        foreach ($tmpBlocks as $key => $block) {
            if(is_json($block['content']))
                $block['content'] = json_decode($block['content'], true);
        }

        return TemplateBlockResource::collection($tmpBlocks);
    }

    public function store(Request $request)
    {
        $template = new Template();

        $template->name = $request->name;
        $template->description = $request->description;
        $template->screenshot = $request->screenshot;

        $template->save();

        $template->tag($request->input('tags', []));

        foreach($request->blocksData as $key => $blockData) {
            $blockData = (object) $blockData;
            $this->updateOrCreateBlock($template, $blockData);
        }

        $template = Template::where('id', $template->id)->first();

        if($template) {
            return new TemplateResource($template);
        }
    }

    protected function updateOrCreateBlock($template, $blockData, $parentId = null)
    {
        $blockData = (object) $blockData;
        $block = $template->saveBlock($blockData, $parentId);

        // save child blocks recursivly
        if(isset($blockData->subItems)) {
            for ($i=0; $i < count($blockData->subItems); $i++) {
                $this->updateOrCreateBlock($template, $blockData->subItems[$i], $block->unique_id);
            }
        }
    }

    public function uploadScreenshot(Request $request)
    {
        if($request->hasFile('file')) {
            $file = $request->file('file');

            $rules = array('file' => 'required|mimes:png,gif,jpeg,jpg'); //'mimes:png,gif,jpeg,txt,pdf,doc'
            $validator = Validator::make(array('file'=> $file), $rules);

            if($validator->passes()) {
                $current = Carbon::now();
                $year = $current->year;
                $month = $current->month;
                $day = $current->day;
                $hour = $current->hour;
                $folder = 'uploads/screenshots/'.$year.'/'.$month.'/'.$day .'/';

                if( !file_exists($folder)) {
                    $oldmask = umask(0);
                    mkdir($folder, 0775, true);
                    umask($oldmask);
                }

                $fileExtension = strtolower($file->getClientOriginalExtension());
                $fileFullName = time().'.'.$fileExtension;

                $file->move($folder, $fileFullName);

                return $folder.$fileFullName;
            } // validator
        }
    }

    public function tags()
    {
        return TagResource::collection(Tag::has('templates')->get());
    }
}

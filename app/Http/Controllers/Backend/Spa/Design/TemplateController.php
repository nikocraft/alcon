<?php

namespace App\Http\Controllers\Backend\Spa\Design;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Core\Content\ContentType;
use App\Models\Core\Content\Template;
use App\Models\Core\Content\TemplateBlock;
use App\Models\Core\Base\Tag;
use App\Models\Core\Settings\Website;

use DB;
use Session;
use Auth;
use Validator;
use Carbon\Carbon;

use App\Http\Resources\TemplateResource;
use App\Http\Resources\TemplateBlockResource;
use App\Http\Resources\TagResource;

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
        $tmpBlocks = TemplateBlock::with('settings')->where('template_id', $id)->get();

        // json decode string to json
        foreach ($tmpBlocks as $key => $block) {
            if($this->isJson($block['content']))
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

        $blocksData = $request->blocksData;

        for ($i=0; $i < count($blocksData); $i++) {
            $this->createOrUpdateBlock($template->id, $blocksData[$i]);
        }

        $template = Template::where('id', $template->id)->first();

        if($template) {
            return new TemplateResource($template);
        }
    }

    protected function createOrUpdateBlock($template_id, $blockData, $parentId = null)
    {
        $blockData = (object) $blockData;
        $tBlock = TemplateBlock::set($template_id, $blockData, $parentId);

        // remove settings from db that have default values
        foreach ($tBlock->getSettings() as $baseKey => $value) {
            if(!array_key_exists($baseKey, $blockData->settings)) {
                $tBlock->removeSetting($baseKey);
            }
        }

        // only save settings that have been customized by user
        foreach ($blockData->settings as $key => $setting) {
            $tBlock->setSetting($key, $setting['value'], $setting['type']);
        }

        // refresh the block so we get latest settings
        $tBlock->load('settings', 'contentBlocks');

        foreach ($tBlock->contentBlocks as $block) {
            $settingIds = array();
            foreach ($tBlock->settings as $key => $value) {
                array_push($settingIds, $value['pivot']['setting_id']);
            }

            $block->settings()->sync($settingIds);
        }

        // process child blocks
        if(isset($blockData->subItems)) {
            for ($i=0; $i < count($blockData->subItems); $i++) {
                $this->createOrUpdateBlock($template_id, $blockData->subItems[$i], $tBlock->unique_id);
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

    protected function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}

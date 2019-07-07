<?php

namespace App\Http\Controllers\Backend\Spa\Media;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Http\Controllers\Controller;

use App\Models\Core\Media\Image;

use Session;
use Auth;
use Validator;
use Carbon\Carbon;
use ImgManager;

use App\Http\Resources\ImageResource;

class MediaController extends Controller
{
    public function upload(Request $request)
    {
        if($request->hasFile('file')) {
            $f = $request->file('file');
            if(is_array($f)) {
                $imageIds = array();
                foreach($f as $file) {
                    $imageId = $this->saveFile($file);
                    array_push($imageIds, $imageId);
                }
                $images = Image::whereIn('id', $imageIds)->select('id', 'path', 'filename', 'extension')->orderBy('created_at', 'desc')->get();
                return ImageResource::collection($images);
            } else {
                $tags = $request->input('tags', []);
                $imageId = $this->saveFile($f, $tags);
                $image = Image::where('id', $imageId)->select('id', 'path', 'filename', 'extension')->first();

                return new ImageResource($image);
            }
        }
    }

    private function saveFile($file, $tags)
    {
        $rules = array('file' => 'required|mimes:png,gif,jpeg,jpg'); //'mimes:png,gif,jpeg,txt,pdf,doc'
        $validator = Validator::make(array('file'=> $file), $rules);

        if($validator->passes()) {
            $image = new Image();
            $current = Carbon::now();
            $year = $current->year;
            $month = $current->month;
            $day = $current->day;
            $hour = $current->hour;
            $folder = 'uploads/'.$year.'/'.$month.'/'.$day .'/';

            $filename = strtolower(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
            $fileExtension = strtolower($file->getClientOriginalExtension());

            $image->slug = $image->slugify($filename);
            $filename = $image->slug;
            $fileFullName = $filename . '.'. $fileExtension;

            if (!is_dir($folder)) {
                @mkdir($folder, 0777, true);
            }
            $file->move($folder, $fileFullName);
            // $giftest = file_get_contents($folder.'/'.$fileFullName);
            // $animated = preg_match('#(\x00\x21\xF9\x04.{4}\x00\x2C.*){2,}#s', $giftest);

            // if($watermark->body != "" && $animated!=1) {
            //     // Add Watermark
            //     $width = ImgResizer::make($folder . '/'.$name)->width();
            //     $height = ImgResizer::make($folder . '/'.$name)->height();
            //     ImgResizer::make($folder . '/'.$name)->text($watermark->body, $width-15, $height-10, function($font) {
            //         $font->file(public_path('fonts/NunitoSans-Regular.ttf'));
            //         $font->size(52);
            //         $font->color('#ffffff');
            //         $font->align('right');
            //         $font->valign('bottom');
            //     })->save();
            // }

            $width = ImgManager::make($folder . $fileFullName)->width();
            $height = ImgManager::make($folder . $fileFullName)->height();

            $resizeWidthMedium = null;
            $resizeHeightMedium = null;
            $resizeWidthLarge = null;
            $resizeHeightLarge = null;

            if($width > $height) {
                $resizeWidthMedium = 600;
                $resizeWidthLarge = 1024;
            }
            else {
                $resizeHeightMedium = 600;
                $resizeHeightLarge = 1024;
            }

            // Generate thumbnail image
            // $thumbnailImageName = $filename .'_thumb.'. $fileExtension;
            // $thumbnailImage = ImgManager::make($folder . $fileFullName)->fit(220, 220);
            // $thumbnailImage->save( $folder . $thumbnailImageName);

            $thumbnailImageName = $filename .'_thumb.'. $fileExtension;
            $thumbnailImage = ImgManager::make($folder . $fileFullName)->fit(480, 480);
            $thumbnailImage->save( $folder . $thumbnailImageName);

            // $image->thumbnail_meta_width = 150;
            // $image->thumbnail_meta_height = 150;

            // Generate medium size image
            $mediumImageName = $filename .'_medium.'. $fileExtension;
            $mediumImage = ImgManager::make($folder . '/'.$fileFullName)->resize($resizeWidthMedium, $resizeHeightMedium, function ($constraint) {
                $constraint->aspectRatio();
            });
            $mediumImage->save( $folder . $mediumImageName);

            // Generate large size image
            $largeImageName = $filename .'_large.'. $fileExtension;
            $largeImage = ImgManager::make($folder . '/'.$fileFullName)->resize($resizeWidthLarge, $resizeHeightLarge, function ($constraint) {
                $constraint->aspectRatio();
            });
            $largeImage->save( $folder . $largeImageName);

            $image->path = $folder;
            $image->filename = $filename;
            $image->extension = $fileExtension;
            $image->user_id = Auth::check() ? Auth::user()->id : 1;
            $image->save();

            $image->tag($tags);
            $image->save();

            return $image->id;
        } // validator
    }
}

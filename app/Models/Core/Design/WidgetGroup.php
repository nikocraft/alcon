<?php

namespace App\Models\Core\Design;

use Illuminate\Database\Eloquent\Model;

use App\Models\Traits\HasWidgets;

class WidgetGroup extends Model
{
    use HasWidgets;

    protected $table = "widget_groups";

    protected $fillable = ['id', 'title', 'layout', 'location', 'filter_mode', 'filter_data', 'meta'];

    protected $casts = [
        'filter_data' => 'array',
        'meta' => 'array',
    ];

    protected $dates = [
        'created_at',
        'deleted_at',
        'updated_at'
    ];

    public function isVisibleOn($contentType, $contentId = null) 
    {
        $filterData = $this->filter_data;

        $visible = array_key_exists($contentType, $filterData);

        if($visible) {
            $contentIds = $filterData[$contentType];
            if(empty($contentIds)) {
                return true;
            } else {
                return in_array($contentId, $contentIds);
            }
        }

        return false;
    }
}

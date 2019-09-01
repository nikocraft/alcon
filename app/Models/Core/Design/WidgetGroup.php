<?php

namespace App\Models\Core\Design;

use Illuminate\Database\Eloquent\Model;

use App\Models\Traits\HasWidgets;
use App\Models\Traits\HasSettings;

class WidgetGroup extends Model
{
    use HasWidgets;
    use HasSettings;

    protected $table = "widget_groups";

    protected $fillable = ['id'];

    protected $casts = [
        'filter_data' => 'array',
        'settings' => 'array',
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

<?php

namespace App\Models\Core\Content\Block;

use Illuminate\Database\Eloquent\Model;

class ExcerptBlock extends Model
{
    private $settings = array();
    public function getDefaults()
    {
        $settings['blockTitle'] = 'Excerpt';
        $settings['renderTitle'] = false;
        $settings['customClass'] = '';

        return $settings;
    }
}

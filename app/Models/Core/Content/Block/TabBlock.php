<?php

namespace App\Models\Core\Content\Block;

use Illuminate\Database\Eloquent\Model;

class TabBlock extends Model
{
    private $settings = array();
    public function getDefaults()
    {
        $settings['blockTitle'] = '';
        $settings['renderTitle'] = false;
        $settings['blockRef'] = '';
        $settings['customClass'] = '';

        $settings['paddingResponsive'] = '0';
        $settings['padding'] = '0px';
        $settings['paddingLarge'] = '';
        $settings['paddingMedium'] = '';
        $settings['paddingSmall'] = '';
        $settings['paddingExtraSmall'] = '';

        $settings['css'] = '';

        return $settings;
    }
}

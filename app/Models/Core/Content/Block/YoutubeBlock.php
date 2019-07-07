<?php

namespace App\Models\Core\Content\Block;

use Illuminate\Database\Eloquent\Model;

class YoutubeBlock extends Model
{
    private $settings = array();
    public function getDefaults()
    {
        $settings['blockTitle'] ='Video';
        $settings['renderTitle'] = false;
        $settings['blockRef'] = '';
        $settings['customClass'] = '';
        $settings['position'] ='center';

        $settings['backgroundColorAdvanced'] = '0';
        $settings['backgroundColor'] = '';
        $settings['backgroundColorHover'] = '';
        $settings['backgroundColorActive'] = '';
        $settings['backgroundColorFocus'] = '';

        $settings['flexResponsive'] = '0';
        $settings['flex'] = '0 1 auto';
        $settings['flexLarge'] = '';
        $settings['flexMedium'] = '';
        $settings['flexSmall'] = '';
        $settings['flexExtraSmall'] = '';

        $settings['alignSelfResponsive'] = '0';
        $settings['alignSelf'] = 'auto';
        $settings['alignSelfLarge'] = '';
        $settings['alignSelfMedium'] = '';
        $settings['alignSelfSmall'] = '';
        $settings['alignSelfExtraSmall'] = '';

        $settings['widthResponsive'] = '0';
        $settings['width'] = '100%';
        $settings['widthLarge'] = '';
        $settings['widthMedium'] = '';
        $settings['widthSmall'] = '';
        $settings['widthExtraSmall'] = '';

        $settings['heightResponsive'] = '0';
        $settings['height'] = '450px';
        $settings['heightLarge'] = '';
        $settings['heightMedium'] = '';
        $settings['heightSmall'] = '';
        $settings['heightExtraSmall'] = '';

        $settings['paddingResponsive'] = '0';
        $settings['padding'] = '0px';
        $settings['paddingLarge'] = '';
        $settings['paddingMedium'] = '';
        $settings['paddingSmall'] = '';
        $settings['paddingExtraSmall'] = '';

        $settings['marginResponsive'] = '0';
        $settings['margin'] = '0px 0px 15px 0px';
        $settings['marginLarge'] = '';
        $settings['marginMedium'] = '';
        $settings['marginSmall'] = '';
        $settings['marginExtraSmall'] = '';

        $settings['css'] = '';

        return $settings;
    }
}

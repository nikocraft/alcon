<?php

namespace App\Models\Core\Content\Block;

use Illuminate\Database\Eloquent\Model;

class ImageBlock extends Model
{
    private $settings = array();
    public function getDefaults()
    {
        $settings['blockTitle'] = 'Image';
        $settings['renderTitle'] = false;
        $settings['blockRef'] = '';
        $settings['customClass'] = '';

        $settings['imageSize'] = 'original';
        $settings['imageResponsive'] = 'true';
        $settings['imagePosition'] = 'center';

        $settings['backgroundColorAdvanced'] = '0';
        $settings['backgroundColor'] = '';
        $settings['backgroundColorHover'] = '';
        $settings['backgroundColorActive'] = '';
        $settings['backgroundColorFocus'] = '';

        $settings['displayResponsive'] = '0';
        $settings['display'] = 'block';
        $settings['displayLarge'] = '';
        $settings['displayMedium'] = '';
        $settings['displaySmall'] = '';
        $settings['displayExtraSmall'] = '';

        $settings['flexResponsive'] = '0';
        $settings['flex'] = '0 1 auto';
        $settings['flexLarge'] = '';
        $settings['flexMedium'] = '';
        $settings['flexSmall'] = '';
        $settings['flexExtraSmall'] = '';

        $settings['widthResponsive'] = '0';
        $settings['width'] = 'auto';
        $settings['widthLarge'] = '';
        $settings['widthMedium'] = '';
        $settings['widthSmall'] = '';
        $settings['widthExtraSmall'] = '';

        $settings['alignSelfResponsive'] = '0';
        $settings['alignSelf'] = 'auto';
        $settings['alignSelfLarge'] = '';
        $settings['alignSelfMedium'] = '';
        $settings['alignSelfSmall'] = '';
        $settings['alignSelfExtraSmall'] = '';

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

        $settings['onClick'] = 'lightbox';
        $settings['target'] = '_self';
        $settings['link'] = '';

        $settings['css'] = '';

        return $settings;
    }
}

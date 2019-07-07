<?php

namespace App\Models\Core\Content\Block;

use Illuminate\Database\Eloquent\Model;

class TextBlock extends Model
{
    private $settings = array();
    public function getDefaults()
    {
        $settings['blockTitle'] ='Text';
        $settings['renderTitle'] = false;
        $settings['blockRef'] = '';
        $settings['customClass'] = '';
        $settings['textAlign'] = 'left';
        $settings['fontWeight'] = '';

        $settings['fontSizeResponsive'] = '0';
        $settings['fontSize'] = '';
        $settings['fontSizeLarge'] = '';
        $settings['fontSizeMedium'] = '';
        $settings['fontSizeSmall'] = '';
        $settings['fontSizeExtraSmall'] = '';

        $settings['fontLineHeightResponsive'] = '0';
        $settings['fontLineHeight'] = '';
        $settings['fontLineHeightLarge'] = '';
        $settings['fontLineHeightMedium'] = '';
        $settings['fontLineHeightSmall'] = '';
        $settings['fontLineHeightExtraSmall'] = '';

        $settings['textShadowAdvanced'] = '0';
        $settings['textShadow'] = '0px 0px 0px transparent';
        $settings['textShadowHover'] = '0px 0px 0px transparent';
        $settings['textShadowActive'] = '0px 0px 0px transparent';
        $settings['textShadowFocus'] = '0px 0px 0px transparent';

        $settings['textColorAdvanced'] = '0';
        $settings['textColor'] = '';
        $settings['textColorHover'] = '';
        $settings['textColorActive'] = '';
        $settings['textColorFocus'] = '';

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

        $settings['onClick'] = 'nothing';
        $settings['target'] = '_self';
        $settings['link'] = '';

        $settings['css'] = '';

        return $settings;
    }
}

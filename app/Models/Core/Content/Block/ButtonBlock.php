<?php

namespace App\Models\Core\Content\Block;

use Illuminate\Database\Eloquent\Model;

class ButtonBlock extends Model
{
    private $settings = array();
    public function getDefaults()
    {
        $settings['blockTitle'] = 'Button';
        $settings['renderTitle'] = false;
        $settings['blockRef'] = '';
        $settings['buttonText'] = 'Button';
        $settings['customClass'] = '';
        $settings['textTag'] = 'h3';

        $settings['alignButton'] = 'flex-start';
        $settings['fontWeight'] = '';

        $settings['fontSizeResponsive'] = '0';
        $settings['fontSize'] = '';
        $settings['fontSizeLarge'] = '';
        $settings['fontSizeMedium'] = '';
        $settings['fontSizeSmall'] = '';
        $settings['fontSizeExtraSmall'] = '';

        $settings['fontLineHeightResponsive'] = '0';
        $settings['fontLineHeight'] = '1.0em';
        $settings['fontLineHeightLarge'] = '';
        $settings['fontLineHeightMedium'] = '';
        $settings['fontLineHeightSmall'] = '';
        $settings['fontLineHeightExtraSmall'] = '';

        $settings['textColorAdvanced'] = '0';
        $settings['textColor'] = 'white';
        $settings['textColorHover'] = '';
        $settings['textColorActive'] = '';
        $settings['textColorFocus'] = '';

        $settings['borderAdvanced'] = '0';
        $settings['border'] = '';
        $settings['borderHover'] = '';
        $settings['borderActive'] = '';
        $settings['borderFocus'] = '';

        $settings['borderRadius'] = '0px';

        $settings['backgroundColorAdvanced'] = '0';
        $settings['backgroundColor'] = '#1CA8D4';
        $settings['backgroundColorHover'] = '';
        $settings['backgroundColorActive'] = '';
        $settings['backgroundColorFocus'] = '';

        $settings['textShadowAdvanced'] = '0';
        $settings['textShadow'] = '';
        $settings['textShadowHover'] = '';
        $settings['textShadowActive'] = '';
        $settings['textShadowFocus'] = '';

        $settings['boxShadowAdvanced'] = '0';
        $settings['boxShadow'] = '';
        $settings['boxShadowHover'] = '';
        $settings['boxShadowActive'] = '';
        $settings['boxShadowFocus'] = '';

        $settings['displayResponsive'] = '0';
        $settings['display'] = 'block';
        $settings['displayLarge'] = '';
        $settings['displayMedium'] = '';
        $settings['displaySmall'] = '';
        $settings['displayExtraSmall'] = '';

        $settings['alignSelfResponsive'] = '0';
        $settings['alignSelf'] = 'auto';
        $settings['alignSelfLarge'] = '';
        $settings['alignSelfMedium'] = '';
        $settings['alignSelfSmall'] = '';
        $settings['alignSelfExtraSmall'] = '';

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

        $settings['paddingResponsive'] = '0';
        $settings['padding'] = '10px 45px';
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

        $settings['onClick'] = 'open-link';
        $settings['target'] = '_self';
        $settings['link'] = '';

        $settings['css'] = '';

        return $settings;
    }
}

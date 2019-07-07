<?php

namespace App\Models\Core\Content\Block;

use Illuminate\Database\Eloquent\Model;

class InputBlock extends Model
{
    private $settings = array();
    public function getDefaults()
    {
        $settings['blockTitle'] ='Input';
        $settings['renderTitle'] = false;
        $settings['blockRef'] = '';
        $settings['required'] = 0;
        $settings['minCharactersRequired'] = 1;
        $settings['maxCharactersAllowed'] = 120;

        $settings['customClass'] = '';
        $settings['fontFamily'] = '';
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

        $settings['displayResponsive'] = '0';
        $settings['display'] = 'block';
        $settings['displayLarge'] = 'block';
        $settings['displayMedium'] = 'block';
        $settings['displaySmall'] = 'block';
        $settings['displayExtraSmall'] = 'block';

        $settings['alignSelfResponsive'] = '0';
        $settings['alignSelf'] = 'auto';
        $settings['alignSelfLarge'] = 'auto';
        $settings['alignSelfMedium'] = 'auto';
        $settings['alignSelfSmall'] = 'auto';
        $settings['alignSelfExtraSmall'] = 'auto';

        $settings['flexResponsive'] = '0';
        $settings['flex'] = '0 1 auto';
        $settings['flexLarge'] = '0 1 auto';
        $settings['flexMedium'] = '0 1 auto';
        $settings['flexSmall'] = '0 1 auto';
        $settings['flexExtraSmall'] = '0 1 auto';

        $settings['widthResponsive'] = '0';
        $settings['width'] = 'auto';
        $settings['widthLarge'] = 'auto';
        $settings['widthMedium'] = 'auto';
        $settings['widthSmall'] = 'auto';
        $settings['widthExtraSmall'] = 'auto';

        $settings['paddingResponsive'] = '0';
        $settings['padding'] = '0px';
        $settings['paddingLarge'] = '0px';
        $settings['paddingMedium'] = '0px';
        $settings['paddingSmall'] = '0px';
        $settings['paddingExtraSmall'] = '0px';

        $settings['marginResponsive'] = '0';
        $settings['margin'] = '0px 0px 15px 0px';
        $settings['marginLarge'] = '0px 0px 15px 0px';
        $settings['marginMedium'] = '0px 0px 15px 0px';
        $settings['marginSmall'] = '0px 0px 15px 0px';
        $settings['marginExtraSmall'] = '0px 0px 15px 0px';

        $settings['onClick'] = 'nothing';
        $settings['link'] = '';
        $settings['target'] = '_self';

        $settings['css'] = '';
        return $settings;
    }
}

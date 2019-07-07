<?php

namespace App\Models\Core\Content\Block;

use Illuminate\Database\Eloquent\Model;

class ColumnsBlock extends Model
{
    private $settings = array();
    public function getDefaults()
    {
        $settings['blockTitle'] = 'Columns';
        $settings['renderTitle'] = false;
        $settings['blockRef'] = '';
        $settings['customClass'] = '';
        $settings['contentRenderStyle'] = 'fluid';

        $settings['minHeight'] = '180px';

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

        $settings['heightResponsive'] = '0';
        $settings['height'] = 'auto';
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
        $settings['margin'] = '0px auto';
        $settings['marginLarge'] = '';
        $settings['marginMedium'] = '';
        $settings['marginSmall'] = '';
        $settings['marginExtraSmall'] = '';

        $settings['columnSpacing'] = '2px';
        $settings['selectedColumns'] = 'two-equal';

        $settings['backgroundColorAdvanced'] = '0';
        $settings['backgroundColor'] = '';
        $settings['backgroundColorHover'] = '';
        $settings['backgroundColorActive'] = '';
        $settings['backgroundColorFocus'] = '';

        $settings['backgroundImage'] = '';
        $settings['backgroundStyle'] = 'scroll';
        $settings['backgroundSize'] = 'cover';
        $settings['backgroundSizeManual'] = '100%';
        $settings['backgroundPosition'] = 'center';
        $settings['backgroundRepeat'] = 'repeat';

        $settings['css'] = '';

        return $settings;
    }
}

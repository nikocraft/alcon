<?php

namespace App\Models\Core\Content\Block;

use Illuminate\Database\Eloquent\Model;

class ColumnBlock extends Model
{
    private $settings = array();
    public function getDefaults()
    {
        $settings['blockTitle'] = 'Column';
        $settings['renderTitle'] = false;
        $settings['blockRef'] = '';
        $settings['customClass'] = '';
        $settings['width'] = '50%';

        $settings['columnSpacing'] = '2px';
        $settings['selectedColumns'] = 'two-equal';

        $settings['displayResponsive'] = '0';
        $settings['display'] = 'block';
        $settings['displayLarge'] = '';
        $settings['displayMedium'] = '';
        $settings['displaySmall'] = '';
        $settings['displayExtraSmall'] = '';

        $settings['flexDirectionResponsive'] = '0';
        $settings['flexDirection'] = 'column';
        $settings['flexDirectionLarge'] = '';
        $settings['flexDirectionMedium'] = '';
        $settings['flexDirectionSmall'] = '';
        $settings['flexDirectionExtraSmall'] = '';

        $settings['flexWrapResponsive'] = '0';
        $settings['flexWrap'] = 'nowrap';
        $settings['flexWrapLarge'] = '';
        $settings['flexWrapMedium'] = '';
        $settings['flexWrapSmall'] = '';
        $settings['flexWrapExtraSmall'] = '';

        $settings['justifyContentResponsive'] = '0';
        $settings['justifyContent'] = 'center';
        $settings['justifyContentLarge'] = '';
        $settings['justifyContentMedium'] = '';
        $settings['justifyContentSmall'] = '';
        $settings['justifyContentExtraSmall'] = '';

        $settings['alignItemsResponsive'] = '0';
        $settings['alignItems'] = 'center';
        $settings['alignItemsLarge'] = '';
        $settings['alignItemsMedium'] = '';
        $settings['alignItemsSmall'] = '';
        $settings['alignItemsExtraSmall'] = '';

        $settings['alignContentResponsive'] = '0';
        $settings['alignContent'] = 'flex-start';
        $settings['alignContentLarge'] = '';
        $settings['alignContentMedium'] = '';
        $settings['alignContentSmall'] = '';
        $settings['alignContentExtraSmall'] = '';

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

        $settings['minHeight'] = '180px';

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

        $settings['onClick'] = 'nothing';
        $settings['target'] = '_self';
        $settings['link'] = '';

        $settings['css'] = '';

        return $settings;
    }

}

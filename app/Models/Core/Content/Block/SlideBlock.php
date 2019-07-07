<?php

namespace App\Models\Core\Content\Block;

use Illuminate\Database\Eloquent\Model;

class SlideBlock extends Model
{
    private $settings = array();
    public function getDefaults()
    {
        $settings['blockTitle'] = 'Slide';
        $settings['renderTitle'] = false;
        $settings['blockRef'] = '';
        $settings['backgroundColorAdvanced'] = '0';
        $settings['backgroundColor'] = '';
        $settings['backgroundColorHover'] = '';
        $settings['backgroundColorActive'] = '';
        $settings['backgroundColorHover'] = '';

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

        $settings['onClick'] = 'nothing';
        $settings['target'] = '_self';
        $settings['link'] = '';

        $settings['css'] = '';

        return $settings;
    }
}

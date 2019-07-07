<?php

namespace App\Models\Core\Content\Block;

use Illuminate\Database\Eloquent\Model;

class ImagesBlock extends Model
{
    private $settings = array();
    public function getDefaults()
    {
        $settings['blockTitle'] = 'Images';
        $settings['renderTitle'] = false;
        $settings['blockRef'] = '';
        $settings['customClass'] = '';
        $settings['spaceBetweenImages'] = '2px';

        $settings['imageBorder'] ='0px solid transparent';
        $settings['imageBorderRadius'] ='0px';

        $settings['imageSizeResponsive'] = '0';
        $settings['imageSize'] = 'large';
        $settings['imageSizeLarge'] = '';
        $settings['imageSizeMedium'] = '';
        $settings['imageSizeSmall'] = '';
        $settings['imageSizeExtraSmall'] = '';

        $settings['imageHeightResponsive'] = '0';
        $settings['imageHeight'] = '300px';
        $settings['imageHeightLarge'] = '';
        $settings['imageHeightMedium'] = '';
        $settings['imageHeightSmall'] = '';
        $settings['imageHeightExtraSmall'] = '';

        $settings['imageResponsive'] = '1';
        $settings['columnsPerRow'] = 3;
        $settings['imageAmount'] = 3;
        $settings['imageWidth'] = '';

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

        $settings['backgroundColorAdvanced'] = '0';
        $settings['backgroundColor'] = '';
        $settings['backgroundColorHover'] = '';
        $settings['backgroundColorActive'] = '';
        $settings['backgroundColorFocus'] = '';

        $settings['onClick'] = 'lightbox';
        $settings['target'] = '_self';

        $settings['css'] = '';

        return $settings;
    }
}

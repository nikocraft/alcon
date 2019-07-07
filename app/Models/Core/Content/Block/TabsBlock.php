<?php

namespace App\Models\Core\Content\Block;

use Illuminate\Database\Eloquent\Model;

class TabsBlock extends Model
{
    private $settings = array();
    public function getDefaults()
    {
        $settings['blockTitle'] = 'Tabs';
        $settings['renderTitle'] = false;
        $settings['blockRef'] = '';
        $settings['tabNavPosition'] = 'top';
        $settings['customClass'] = '';

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

        $settings['tabNavItemPaddingResponsive'] = '0';
        $settings['tabNavItemPadding'] = '8px 16px';
        $settings['tabNavItemPaddingLarge'] = '';
        $settings['tabNavItemPaddingMedium'] = '';
        $settings['tabNavItemPaddingSmall'] = '';
        $settings['tabNavItemPaddingExtraSmall'] = '';

        $settings['tabNavMarginResponsive'] = '0';
        $settings['tabNavMargin'] = '0px';
        $settings['tabNavMarginLarge'] = '';
        $settings['tabNavMarginMedium'] = '';
        $settings['tabNavMarginSmall'] = '';
        $settings['tabNavMarginExtraSmall'] = '';

        $settings['tabNavItemColorAdvanced'] = '0';
        $settings['tabNavItemColor'] = 'white';
        $settings['tabNavItemColorHover'] = 'white';
        $settings['tabNavItemColorActive'] = 'white';
        $settings['tabNavItemColorFocus'] = 'white';

        $settings['tabNavItemBackgroundColorAdvanced'] = '0';
        $settings['tabNavItemBackgroundColor'] = 'white';
        $settings['tabNavItemBackgroundColorHover'] = 'white';
        $settings['tabNavItemBackgroundColorActive'] = 'white';
        $settings['tabNavItemBackgroundColorFocus'] = 'white';

        $settings['marginResponsive'] = '0';
        $settings['margin'] = '0px auto';
        $settings['marginLarge'] = '';
        $settings['marginMedium'] = '';
        $settings['marginSmall'] = '';
        $settings['marginExtraSmall'] = '';

        $settings['css'] = '';

        return $settings;
    }
}

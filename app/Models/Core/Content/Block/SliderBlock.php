<?php

namespace App\Models\Core\Content\Block;

use Illuminate\Database\Eloquent\Model;

class SliderBlock extends Model
{

    private $settings = array();
    public function getDefaults()
    {
        $settings['blockTitle'] = 'Slider';
        $settings['renderTitle'] = false;
        $settings['blockRef'] = '';
        // $settings['position'] = 'absolute';
        $settings['contentRenderStyle'] = 'fluid';
        $settings['width'] = '100%';

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

        $settings['heightResponsive'] = false;
        $settings['height'] = '450px';
        $settings['heightLarge'] = '';
        $settings['heightMedium'] = '';
        $settings['heightSmall'] = '';
        $settings['heightExtraSmall'] = '';

        $settings['showArrows'] =  'allways';
        $settings['showButtons'] = 'allways';

        $settings['autoplay'] = '0';
        $settings['autoplayDuration'] = '6000';

        $settings['loop'] = '0';
        $settings['transition'] = 'slideHorisontal';

        $settings['showProgressBar'] = false;
        $settings['progressBarColor'] = '#007AFF';

        $settings['paddingResponsive'] = '0';
        $settings['padding'] = '0px 0px 15px 0px';
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


    public static function prepareSlides($allBlocks, $subBlocksIds, $block = null) {
        $blocks = [];
        foreach ($subBlocksIds as $key => $blockId) {
            $block = $allBlocks[$blockId];

            if(isset($block->subItems)) {
                $block->subBlocks = self::prepareSlides($allBlocks, $block->subItems);
            }
            $blocks[$key] = $block->toArray();
        }
        return $blocks;
    }
}

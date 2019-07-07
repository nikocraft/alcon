<?php
namespace App\Services;

class RenderContentService
{

    // rules for beautifying inline css
    private static $regex1 = '/(?:^[^{};]+\{(?:\s*[^:{}]*:\h*(?:[\'"]*|!important);|\s)*+\}\r?\n?|^[ \t]*(?=[^{};]*[{}])|^[ \t]*\S+\s*:(?:[ \t\'"]*;|\h*!important\h*;)\h*(?:\r?\n|(?=\}))|^\h*\r?\n)/m';
    private static $regex2 = '/(?:\{\K|(?<!^)\G) *\r?\n? *([^:\r\n]+?)\s*:\s*([^;\r\n]+;)/m';


    public function renderSingle($content, $blocks, $widgets)
    {
        $pageType = 'single';
        $contentType = $content->type;
        $css = $content->css;
        $rootBlocksIds = array();
        $allBlocks = array();

        // Prepare content blocks for rendering
        foreach ($blocks as $key => $block) {
            $block->subItems = array();
        }

        // process content blocks before rendering them
        foreach ($blocks as $key => $block) {
            $allBlocks[$block->unique_id] = $block;
            if ($block->parent_id) {
                $parent = $allBlocks[$block->parent_id];
                $subs = $parent->subItems;
                array_push($subs, $block->unique_id);
                $parent->subItems = $subs;
            } else {
                $rootBlocksIds[$block->unique_id] = $block->unique_id;
            }
        }

        $templateBasePath = 'content.template';
        $templatePathObj = $contentType->getSetting(null, 'templatePath');
        $templatePath = ($templatePathObj) ? $templatePathObj->value : '';
        $rendered = view()->first([
            $templateBasePath.'.'.$templatePath.'.render',
            $templateBasePath.'.default.render'
        ], compact(
            'pageType',
            'contentType',
            'content',
            'rootBlocksIds',
            'allBlocks',
            'widgets',
            'css')
        )->render();

        $rendered = $this->beautifyCss($rendered);

        return $rendered;
    }

    public function renderIndex($contentType, $posts, $widgets)
    {
        $pageType = 'index';
        $templateBasePath = 'content.template';
        $templatePathObj = $contentType->getSetting(null, 'templatePath');
        $templatePath = ($templatePathObj)
            ? $templatePathObj->value
            : '';
        $rendered = view()->first([
            $templateBasePath.'.'.$templatePath.'.render',
            $templateBasePath.'.default.render'
        ], compact(
            'pageType',
            'contentType',
            'posts',
            'widgets'
        ));

        $rendered = $this->beautifyCss($rendered);

        return $rendered;
    }

    private function beautifyCss($rendered)
    {
        $selectStyles = '~<style\b[^>]*>\s*\r?\n\K.*?(?=</style>)~s';
        $selectMediaQueries = '~@media\b[^{}]*\{\h*\r?\n\K(?:[^{}]*\{[^{}]*\})*\s*(?=\})~m';
        $rendered = preg_replace_callback($selectStyles, [$this, 'parseStyles'], $rendered);
        $rendered = preg_replace_callback($selectMediaQueries, [$this, 'parseMediaQueries'], $rendered);

        return $rendered;
    }

    private function parseStyles($matches)
    {
        $firstresult = preg_replace(static::$regex1, '', $matches[0]);
        $result = preg_replace(static::$regex2, "\n    $1: $2", $firstresult);
        return $result;
    }

    private function parseMediaQueries($matches)
    {
        $cfirstresult = preg_replace(static::$regex1, '', $matches[0]);
        $cresult = preg_replace(static::$regex2, "\n    $1: $2", $cfirstresult);
        return preg_replace('/^/m', '  ', $cresult);
    }

}

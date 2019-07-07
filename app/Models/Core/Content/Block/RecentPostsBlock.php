<?php

namespace App\Models\Core\Content\Block;

use Illuminate\Database\Eloquent\Model;

class RecentPostsBlock extends Model
{
    private $settings = array();
    public function getDefaults()
    {
        $settings['blockTitle'] ='Recent Posts';
        $settings['blockRef'] = '';
        $settings['postsType'] = '2';
        $settings['numberOfPosts'] = '10';
        $settings['renderTitle'] = false;
        $settings['renderFeaturedImage'] = true;
        $settings['customClass'] = '';

        $settings['css'] = '';
        return $settings;
    }
}

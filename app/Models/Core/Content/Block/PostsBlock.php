<?php

namespace App\Models\Core\Content\Block;

use Illuminate\Database\Eloquent\Model;

class PostsBlock extends Model
{
    private $settings = array();
    public function getDefaults()
    {
        $settings['blockTitle'] ='Posts';
        $settings['renderTitle'] = false;
        $settings['blockRef'] = '';
        $settings['customClass'] = '';

        $settings['css'] = '';
        return $settings;
    }
}

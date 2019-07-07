<?php 
namespace App\Facades;

class Theme extends \Laraone\Themes\Theme {
    public $name;
    public $assetPath;
    public $viewsPath;

    public function __construct($themeName, $assetPath = null, $viewsPath = null){
        parent::__construct($themeName, $assetPath = null, $viewsPath = null);
    }
}

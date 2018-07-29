<?php
namespace app\assets;
use yii\web\AssetBundle;

class SemanticUIAsset extends AssetBundle
{
    public $basePath = '@webroot/static/libs';
    public $baseUrl = '@web/static/libs';
    public $css = [
        'semantic/semantic.min.css',
    ];
    public $js = [
        'semantic/semantic.min.js',
    ];
    public $depends = [
        'app\assets\JqueryAsset',
    ];
}

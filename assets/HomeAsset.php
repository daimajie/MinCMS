<?php
namespace app\assets;
use yii\web\AssetBundle;

class HomeAsset extends AssetBundle
{
    public $basePath = '@webroot/static/home';
    public $baseUrl = '@web/static/home';
    public $css = [
        'css/index.css',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'app\assets\SemanticUIAsset',
    ];
}

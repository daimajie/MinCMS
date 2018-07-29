<?php
namespace app\assets;
use yii\web\AssetBundle;

class AdminAsset extends AssetBundle
{
    public $basePath = '@webroot/static/admin';
    public $baseUrl = '@web/static/admin';
    public $css = [
        'css/hou.css',
        'css/index.css',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'app\assets\SemanticUIAsset',
    ];
}

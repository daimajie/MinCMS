<?php
namespace app\assets;
use yii\web\AssetBundle;

class JqueryAsset extends AssetBundle
{
    public $basePath = '@webroot/static/libs';
    public $baseUrl = '@web/static/libs';

    public $js = [
        'jquery/jquery-3.3.1.min.js'
    ];

    //放置在head里
    /*public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD,
    ];*/
}

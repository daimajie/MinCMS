<?php
namespace app\assets;
use yii\web\AssetBundle;
use yii\web\View;

class Html5Asset extends AssetBundle
{
    public $publishOptions = false;

    public $jsOptions = [
        'condition' => 'lte IE9',
        'position' => View::POS_HEAD
        ];

    public $css = [
    ];
    public $js = [
        '//cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js',
        '//cdn.bootcss.com/respond.js/1.4.2/respond.min.js',
    ];
}

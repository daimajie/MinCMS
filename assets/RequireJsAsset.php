<?php
namespace app\assets;
use yii\web\AssetBundle;

class RequireJsAsset extends AssetBundle
{
    public $basePath = '@webroot/static/libs';
    public $baseUrl = '@web/static/libs';

    public $js = [
        'requirejs/require.js'
    ];

    //放置在head里
    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD,
    ];

    public function init()
    {
        parent::init();
        $moduleName = \Yii::$app->controller->module->id;
        if($moduleName === 'basic')
            $this->jsOptions['data-main'] = "/static/home/js/main";
        else
            $this->jsOptions['data-main'] = "/static/admin/js/main";
    }
}

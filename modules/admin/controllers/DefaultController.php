<?php
namespace app\modules\admin\controllers;
use Yii;

/**
 * 后台frame框架
 */
class DefaultController extends BaseController
{
    //框架
    public function actionFrame(){
        return $this->render('frame');
    }

    //首页
    public function actionIndex(){
        return $this->render('index');
    }
}

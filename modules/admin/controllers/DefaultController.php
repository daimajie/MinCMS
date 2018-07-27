<?php
namespace app\modules\admin\controllers;
use Yii;

/**
 * 后台frame框架
 */
class DefaultController extends BaseController
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    //框架
    public function actionFrame(){
        $this->layout = 'layout';
        return $this->render('frame');
    }

    //首页
    public function actionIndex(){
        return $this->render('index');
    }

}

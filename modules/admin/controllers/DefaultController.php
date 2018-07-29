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
        return $this->render('frame',[
            'user' => Yii::$app->user->identity,
            'name' => Yii::$app->name,
            'defaultImage' => Yii::$app->params['image'],
        ]);
    }

    //首页
    public function actionIndex(){
        return $this->render('index');
    }

}

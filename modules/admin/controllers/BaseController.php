<?php
namespace app\modules\admin\controllers;
use yii\helpers\VarDumper;
use yii\web\Controller;
use Yii;

class BaseController extends Controller
{
    public function beforeAction($action)
    {
        if(parent::beforeAction($action)){

            //登录后台必须登录状态 再检测权限
            if(Yii::$app->user->isGuest){
                Yii::$app->user->loginRequired();
            }



            return true;
        }
    }

}
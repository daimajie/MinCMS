<?php
namespace app\modules\admin\controllers;
use yii\helpers\VarDumper;
use yii\web\Controller;

class BaseController extends Controller
{
    /*public function beforeAction($action)
    {
        if(parent::beforeAction($action)){
            echo $action->id,$action->controller->id,$action->controller->module->id;die;

            VarDumper::dump($action,10,1);die;
        }
    }*/
}
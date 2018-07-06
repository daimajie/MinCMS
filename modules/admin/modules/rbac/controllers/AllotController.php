<?php
namespace app\modules\admin\modules\rbac\controllers;
use app\models\rbac\AuthAllot;
use app\modules\admin\controllers\BaseController;
use Yii;
use yii\web\Response;

class AllotController extends BaseController
{
    //分配列表
    public function actionIndex(){
        echo 'index';
    }

    //添加分类
    public function actionCreate(){
        $model = new AuthAllot();

        $selectArr = [''=>'选择条目'];
        return $this->render('create',[
            'selectArr' => $selectArr,
            'model' => $model,
        ]);
    }

    //获取可分配的权限和角色
    public function actionGetRoleAndPermission(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        return $post;
    }
}
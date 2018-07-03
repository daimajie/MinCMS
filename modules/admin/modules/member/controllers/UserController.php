<?php
namespace app\modules\admin\modules\member\controllers;
use app\models\member\UserForm;
use app\models\member\SearchUser;
use Yii;
use app\modules\admin\controllers\BaseController;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class UserController extends BaseController
{
    //用户列表
    public function actionIndex(){
        $searchModel = new SearchUser();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        //下拉框初始数据
        $selectArr = ['' => '按话题搜索'];

        return $this->render('index',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'selectArr' => $selectArr
        ]);
    }

    //用户添加
    public function actionCreate(){
        $model = new UserForm();
        $model->scenario = UserForm::SCENARIO_CREATE;

        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->store()){
                //创建新用户成功
                Yii::$app->session->setFlash('success', '创建新用户成功。');
                return $this->redirect(['index']);
            }
            //创建失败 显示表单及错误
        }

        return $this->render('create',[
            'model' => $model,
        ]);
    }

    //用户查看
    public function actionView($id){
        $model = static::getModel($id);
        return $this->render('view',[
            'model' => $model,
        ]);
    }

    //用户编辑
    public function actionUpdate($id){
        $model = static::getModel($id);
        $model->scenario = UserForm::SCENARIO_UPDATE;

        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->renew()){
                Yii::$app->session->setFlash('success', '修改用户成功。');
                return $this->redirect(['index']);
            }
            //编辑失败 显示表单 及错误信息
        }

        return $this->render('create',[
            'model' => $model
        ]);
    }

    //用户删除
    public function actionDelete($id){
        $model = static::getModel($id);

        //删除图片
        if($model->image){
            UserForm::deleteImage($model->image);
        }

        if ($model->delete() !== false){
            //删除成功
            Yii::$app->session->setFlash('success','删除用户成功。');
        }else{
            //删除失败
            Yii::$app->session->setFlash('error', '删除用户失败。');
        }
        return $this->redirect(['index']);
    }

    //模型获取
    public static function getModel($id){
        $id = (int) $id;
        if($id <= 0)
            throw new BadRequestHttpException('请求参数错误。');

        $model = UserForm::findOne($id);

        if(!$model)
            throw new NotFoundHttpException('没有相关数据。');

        return $model;
    }


}
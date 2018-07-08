<?php
namespace app\modules\admin\modules\setting\controllers;
use app\models\setting\SearchFriend;
use app\models\setting\Friend;
use app\modules\admin\controllers\BaseController;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class FriendController extends BaseController
{
    //列表
    public function actionIndex(){
        $searchModel = new SearchFriend();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);



        return $this->render('index',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);


    }

    //添加
    public function actionCreate(){
        $model = new Friend();

        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->save()){
                //添加成功
                Yii::$app->session->setFlash('success', '添加友链成功。');
                return $this->redirect(['index']);
            }
            //添加失败 显示表单和错误信息
        }

        return $this->render('create',[
            'model' => $model,
        ]);
    }

    //编辑
    public function actionUpdate($id){
        $model = static::getModel($id);

        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->save()){
                //添加成功
                Yii::$app->session->setFlash('success', '编辑友链成功。');
                return $this->redirect(['index']);
            }
            //添加失败 显示表单和错误信息
        }

        return $this->render('create',[
            'model' => $model
        ]);

    }

    //查看
    public function actionView($id){

        $model = static::getModel($id);

        return $this->render('view',[
            'model' => $model,
        ]);
    }

    //删除
    public function actionDelete($id){
        $model = static::getModel($id);
        if($model->delete() !== false)
            Yii::$app->session->setFlash('success', '删除友链成功。');
        else
            Yii::$app->session->setFlash('error', '删除友链失败，请重试。');
        return $this->redirect(['index']);
    }

    //获取模型
    protected static function getModel($id){
        $id = (int) $id;
        if($id <= 0)
            throw new BadRequestHttpException('请求参数错误');
        $model = Friend::findOne($id);
        if(empty($model)){

            throw new NotFoundHttpException('没有相关数据。');
        }

        return $model;

    }

    //批量删除
    public function actionBatchDel(){
        //获取参数
        $friend_id = Yii::$app->request->post('friend_id');
        if(!is_array($friend_id))
            throw new BadRequestHttpException('请求参数错误。');

        if(Friend::deleteAll(['in', 'id', $friend_id]) !== false)
            Yii::$app->session->setFlash('success', '批量删除友链成功。');
        else
            Yii::$app->session->setFlash('error', '批量删除友链失败，请重试。');

        return $this->redirect(['index']);
    }



}
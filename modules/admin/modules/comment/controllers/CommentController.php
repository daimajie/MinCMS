<?php
/**
 * Created by PhpStorm.
 * User: lvcheng
 * Date: 18-7-24
 * Time: 上午8:53
 */
namespace app\modules\admin\modules\comment\controllers;
use app\models\collect\Comment;
use app\models\content\Article;
use app\modules\admin\controllers\BaseController;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;
use Yii;

class CommentController extends BaseController
{
    //index
    public function actionIndex(){
        $dataProvider = new ActiveDataProvider([
            'query' => Comment::find(),
        ]);

        return $this->render('index',[
            'dataProvider' => $dataProvider
        ]);
    }

    //update
    public function actionUpdate($id){
        $model = Comment::findOne($id);
        if(!$model)throw new NotFoundHttpException('没有相关数据。');
        $model->scenario = Comment::UPDATE;

        if(Yii::$app->request->isPost){
            $model->load(Yii::$app->request->post());

            //验证
            $model->content = trim($model->content);
            if(!empty($model->content)){
                if($model->save(false)){
                    //更新成功
                    Yii::$app->session->setFlash('success', '更新评论成功。');
                    return $this->redirect(['comment/index']);
                }
            }else{
                $model->addError('content', '内容不能为空。');
            }
        }

        return $this->render('update',[
            'model' =>$model,
        ]);
    }

    //delete
    public function actionDelete($id){
        $model = Comment::findOne($id);
        if (!$model) throw new NotFoundHttpException('没有相关数据。');

        //判断是评论还是回复
        $decr = 1;
        if($model->type == 0){
            //删除该评论的所有回复
            $decr += (int)Comment::deleteAll(['comment_id'=>$model->id]);
        }

        //删除
        if($model->delete() !== false){
            //递减评论数目
            Article::updateAllCounters(['comment'=>-$decr],['id'=>$model->article_id]);
            Yii::$app->session->setFlash('success', '删除评论成功。');

        }else{
            Yii::$app->session->setFlash('error', '删除评论失败，请重试。');
        }
        return $this->redirect(['comment/index']);



    }




}
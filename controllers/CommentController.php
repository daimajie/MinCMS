<?php
namespace app\controllers;

use app\models\collect\Comment;
use yii\base\Exception;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;

class CommentController extends BaseController
{
    //获取评论列表***
    public function actionComments($id){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{

            //请求方式
            if(!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方式不被允许。');

            //参数
            $id = (int) $id;
            if($id <= 0)
                throw new BadRequestHttpException('请求参数错误。');

            //获取数据
            $data = Comment::getComments($id, true);
            if(empty($data['comments'])){
                throw new NotFoundHttpException('没有找到相关数据。');
            }

            //处理数据 （生成头像 格式化时间）
            $data['comments'] = Comment::formatData($data['comments']);

            return [
                'errno' => 0,
                'data' => $data
            ];




        }catch (MethodNotAllowedHttpException $e){

            return $this->redirect(['index/index']);
        }catch (Exception $e){

            return ['errno'=>1, 'message'=>$e->getMessage()];
        }



    }

    //提交评论***
    public function actionComment(){
        Yii::$app->response->format = Response::FORMAT_JSON;

        try{
            //判断请求方式
            if(!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方式不被允许。');


            //判断登录状态
            if(Yii::$app->user->isGuest)
                throw new ForbiddenHttpException('请先登录再进行此操作。');

            //判断请求数据
            $article_id = (int) Yii::$app->request->post('id');
            $content = trim(Yii::$app->request->post('content'));
            if($article_id <= 0 || empty($content)){
                throw new BadRequestHttpException('请求参数错误，请重试。');
            }

            //判断是否密集提交
            if (Comment::interval($article_id, Yii::$app->user->id))
                throw new Exception('请间隔两分钟，再提交评论。');

            //写入数据
            $comment = new Comment();
            $comment->scenario = Comment::COMMENT;
            //$comment->user_id = Yii::$app->user->id;
            $comment->article_id = $article_id;
            $comment->type = 0; //0是评论 1是回复
            $comment->comment_id = 0; //评论没有评论id
            //$comment->created_at = time();
            $comment->content = $content;

            $ret = $comment->save();

            //返回数据
            if($ret === false){
                //评论失败
                throw new Exception('提交评论失败，请重试。');
            }
            return [
                'errno' => 0,
                'message' => '提交评论成功。'
            ];



        }catch (MethodNotAllowedHttpException $e){
            return $this->redirect(['index/index']);
        }catch (Exception $e){
            return ['errno'=>1, 'message'=>$e->getMessage()];
        }



    }


    //删除评论
    public function actionDelete(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            //请求方式
            if(!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方式不被允许。');

            //判断是否登录
            if(Yii::$app->user->isGuest)
                throw new ForbiddenHttpException('请登录后再进行删除。');

            //接受参数
            $aid = (int) Yii::$app->request->get('id');
            $cid = (int) Yii::$app->request->post('id');
            if($aid <= 0 || $cid <= 0)
                throw new BadRequestHttpException('请求参数错误。');

            $model = Comment::find()->where(['and', ['article_id'=>$aid], ['id'=>$cid], ['user_id'=>Yii::$app->user->id]])->one();
            if(empty($model))
                throw new NotFoundHttpException('没有相关数据。');

            //删除该评论下的所有回复
            if($model->type == 0){
                Comment::deleteAll(['and', ['comment_id'=>$cid], ['type'=>1]]);
            }

            if($model->delete() === false)
                throw new Exception('删除错误，请重试。');


            return ['errno'=>0, 'message'=>'删除评论成功。'];



        }catch (MethodNotAllowedHttpException $e){

            return $this->redirect(['index/index']);
        }catch (Exception $e){

            return ['errno'=>1, 'message'=>$e->getMessage()];
        }
    }


    //提交回复
    public function actionReply($id){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            if(!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方式不被允许。');

            //判断是否登录
            if(Yii::$app->user->isGuest)
                throw new ForbiddenHttpException('请登录后再进行回复。');

            //检测参数
            $article_id = (int) $id;
            if($article_id <= 0) throw new BadRequestHttpException('请求参数错误。');
            $comment_id = (int) Yii::$app->request->post('comment_id');
            $comment_user = htmlspecialchars(Yii::$app->request->post('comment_user'));
            $content = htmlspecialchars(Yii::$app->request->post('content'));
            if($comment_id <= 0 || empty($comment_user) || empty($content))
                throw new BadRequestHttpException('请求参数错误。');

            //判断是否频繁提交回复
            if(Comment::interval($article_id, Yii::$app->user->id)){
                throw new Exception('请间隔两分钟在进行回复操作。');
            }


            //写入数据
            $model = new Comment();
            $model->scenario = Comment::REPLY;
            $model->article_id = $article_id;
            $model->comment_id = $comment_id;
            $model->type = 1; //0是评论 1是回复
            $model->content = '@' . $comment_user. '  ' .$content;

            if($model->save() === false)
                throw new Exception('提交回复失败，请重试。');


            return [
                'errno' => 0,
                'message' => '提交回复成功。'
            ];

        }catch (MethodNotAllowedHttpException $e){

            return $this->redirect(['index/index']);
        }catch (Exception $e){

            return ['errno'=>1, 'message'=>$e->getMessage()];
        }


    }




}
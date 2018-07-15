<?php
namespace app\controllers;

use app\models\collect\Collect;
use app\models\collect\Comment;
use app\models\content\Article;
use app\models\content\Content;
use yii\base\Exception;
use yii\helpers\HtmlPurifier;
use yii\helpers\VarDumper;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use Yii;
use yii\web\Response;

class ArticleController extends BaseController
{
    //文章详情
    public function actionIndex($id){
        $id = (int) $id;
        if($id <= 0) throw new BadRequestHttpException('请求错误。');

        //文章详情
        $article = Article::getDetail($id);
        if(!$article) throw new NotFoundHttpException('没有相关数据。');

        //缓存文章内容
        $cache = Yii::$app->cache;
        $dependency = new \yii\caching\DbDependency([
            'sql' => 'select updated_at from {{%article}} where id=' . $id,
        ]);
         $article['content'] = $cache->getOrSet('content', function () use ($id) {
                $ret = Content::find()->where(['id'=>$id])->asArray()->one();
                $ret['content'] = HtmlPurifier::process($ret['content']);
                return $ret;

        },2*3600, $dependency);

        //获取上一篇写一篇
        $prevNext = Article::prevAndNext($article['id'], $article['topic']['id']);

        //是否喜欢收藏过该文章
        $isCollect = Collect::isCollect($article['id']);

        //获取评论信息
        $comment = Comment::getComments($article['id']);

//        VarDumper::dump($comment,10,1);die;




        return $this->render('index',[
            'article' => $article,
            'prevNext' => $prevNext,
            'isCollect' =>$isCollect,
            'comments' => $comment['comments'],
            'pagination' => $comment['pagination']
        ]);
    }

    //添加喜欢
    public function actionCollect(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            if (!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方式不被允许。');

            //检测是否登录
            if(Yii::$app->user->isGuest)
                throw new ForbiddenHttpException('请先登录，再进行此操作。');

            //接受参数
            $article_id = (int) Yii::$app->request->post('article_id');
            if($article_id <= 0)
                throw new BadRequestHttpException('请求参数错误。');

            //判断是否已经收藏
            $exist = Collect::find()
                ->where(['and', ['user_id'=>Yii::$app->user->id], ['article_id'=>$article_id]])
                ->andWhere(['type'=>1])
                ->count();
            if($exist >= 1)
                throw new Exception('已经收藏过了，请不要重复操作。');

            //保存数据
            $model = new Collect();
            $model->article_id = $article_id;
            $model->type = 1; //1为收藏 0为喜欢
            if($model->save() === false){
                throw new Exception('添加收藏失败，请重试。');
            }


            return [
                'errno' => 0,
                'message' => '添加收藏成功。'
            ];

        }catch (MethodNotAllowedHttpException $e){

            return $this->redirect(['index/index']);
        }catch (Exception $e){

            return ['errno'=>1, 'message'=>$e->getMessage()];
        }


    }

    //添加收藏
    public function actionLikes(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            if (!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方式不被允许。');

            //检测是否登录
            if(Yii::$app->user->isGuest)
                throw new ForbiddenHttpException('请先登录，再进行此操作。');

            //接受参数
            $article_id = (int) Yii::$app->request->post('article_id');
            if($article_id <= 0)
                throw new BadRequestHttpException('请求参数错误。');

            //判断是否已经收藏
            $exist = Collect::find()
                ->where(['and', ['user_id'=>Yii::$app->user->id], ['article_id'=>$article_id]])
                ->andWhere(['type'=>0])
                ->count();
            if($exist >= 1)
                throw new Exception('已经喜欢过了，请不要重复操作。');

            //保存数据
            $model = new Collect();
            $model->article_id = $article_id;
            $model->type = 0; //1为收藏 0为喜欢
            if($model->save() === false){
                throw new Exception('添加喜欢失败，请重试。');
            }


            return [
                'errno' => 0,
                'message' => '添加喜欢成功。'
            ];

        }catch (MethodNotAllowedHttpException $e){

            return $this->redirect(['index/index']);
        }catch (Exception $e){

            return ['errno'=>1, 'message'=>$e->getMessage()];
        }
    }

    //提交评论
    /*public function actionComment(){
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

            //写入数据
            $comment = new Comment();
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

    }*/

    //获取评论列表
    /*public function actionComments($id){
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



    }*/




}
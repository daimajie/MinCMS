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
        //VarDumper::dump($article,10,1);die;
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

        //添加阅读数
        Article::updateAllCounters(['visited'=>1],['id'=>$article['id']]);


        return $this->render('index',[
            'article' => $article,
            'prevNext' => $prevNext,
            'isCollect' =>$isCollect,
            'comments' => $comment['comments'],
            'pagination' => $comment['pagination']
        ]);
    }

    //添加收藏
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

            //收藏累加
            Article::updateAllCounters(['collect'=>1],['id'=>$article_id]);
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

    //添加喜欢
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

            //喜欢累加
            Article::updateAllCounters(['likes'=>1],['id'=>$article_id]);
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





}
<?php
namespace app\controllers;

use app\models\content\Article;
use app\models\content\Topic;
use yii\helpers\VarDumper;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class TopicController extends BaseController
{
    //列表
    public function actionIndex($id){
        $id = (int) $id;
        if($id <= 0)
            throw new BadRequestHttpException('请求参数错误。');

        //获取话题信息 携带标签
        $topic = Topic::find()->where(['id'=>$id])->with('tags')->one();

        if(empty($topic))
            throw new NotFoundHttpException('没有相关数据。');

        //获取文章列表
        $data = Article::getArticlesByTopic($topic->id);



        return $this->render('index',[
            'topic' => $topic,
            'articles' => $data['articles'],
            'pagination' => $data['pagination']
        ]);
    }

    //所有话题
    public function actionTopics(){

        $data = Topic::getTopics();

        return $this->render('topics',[
            'topics' => $data['topics'],
            'pagination' => $data['pagination'],
        ]);
    }
}
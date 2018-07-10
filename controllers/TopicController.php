<?php
namespace app\controllers;

use app\models\content\Article;
use app\models\content\Topic;
use yii\helpers\VarDumper;
use yii\web\BadRequestHttpException;

class TopicController extends BaseController
{
    //文章列表
    public function actionIndex($id){
        $id = (int) $id;
        if($id <= 0)
            throw new BadRequestHttpException('请求参数错误。');

        //获取话题信息 携带标签
        $topic = Topic::find()->where(['id'=>$id])->with('tags')->one();

        //获取文章列表
        $data = Article::getArticlesByTopic($topic->id);



        return $this->render('index',[
            'topic' => $topic,
            'articles' => $data['articles'],
            'pagination' => $data['pagination']
        ]);
    }
}
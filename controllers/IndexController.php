<?php
namespace app\controllers;

use app\models\content\Article;
use app\models\content\Topic;
use app\models\setting\Friend;
use yii\helpers\VarDumper;

class IndexController extends BaseController
{
    //首页
    public function actionIndex(){

        //获取文章列表
        $data = Article::getArticles();

        //获取热门文章9条
        $hot = Article::getHotArticles();

        //获取热门话题
        $hotTopic = Topic::getHotTopic();

        //获取友链
        $friends = Friend::find()->orderBy(['sort'=>SORT_ASC,'id'=>SORT_DESC])->limit(15)->asArray()->all();


        return $this->render('index',[
            'articles' => $data['articles'],
            'pagination' => $data['pagination'],
            'hot' => $hot,
            'hotTopic' => $hotTopic,
            'friends' => $friends,
        ]);
    }




}
<?php
namespace app\components\rules;
use yii\rbac\Rule;
use Yii;
use app\models\content\Article;
use app\models\content\Topic;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class AuthorRule extends Rule
{
    const FORK_ARTICLE = 'article';
    const FORK_TOPIC = 'topic';

    public $name = 'isAuthor';



    public function execute($user, $item, $params)
    {
        //获取当前id
        $id = (int) Yii::$app->request->get('id');
        if($id <= 0) throw new BadRequestHttpException('请求错误。');

        //当前请求控制器
        $controllerId = Yii::$app->controller->id;

        //判断分支
        if($controllerId === self::FORK_ARTICLE){
            //请求的是文章管理
            $article = Article::findOne($id);

            if(!$article) throw new NotFoundHttpException('没有相关数据');
            return $article->user_id == $user;

        }elseif($controllerId === self::FORK_TOPIC){
            //请求的是分类管理
            $topic = Topic::findOne($id);

            if(!$topic) throw new NotFoundHttpException('没有相关数据');
            return $topic->user_id == $user;

        }else{
            return false;
        }

    }
}
<?php
namespace app\controllers;

use app\models\content\Article;
use app\models\member\User;
use yii\data\Pagination;
use yii\helpers\VarDumper;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use Yii;

class AuthorController extends BaseController
{
    public function actionIndex(){

        //获取所有作者信息
        $query = User::find()->select(['id','username', 'image'])->where(['!=', 'group', 0]);
        $count = $query->count();

        $pagination =  new Pagination(['totalCount' => $count, 'pageSize' => 30]);

        $authors = $query->orderBy(['count'=>SORT_DESC,'created_at'=>SORT_DESC])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        return $this->render('index',[
            'authors' => $authors,
            'pagination' => $pagination
        ]);
    }

    public function actionView($id){
        $id = (int) $id;
        if($id <= 0) throw new BadRequestHttpException('请求参数错误。');

        $user = User::findOne($id);
        if(!$user) throw new NotFoundHttpException('没有相关数据。');

        //是否是后台用户
        if(
            !Yii::$app->authManager->checkAccess($id,'admin') &&
            !Yii::$app->authManager->checkAccess($id,'author')
        ) throw new BadRequestHttpException('请求错误。');

        //获取指定作者的所有文章
        $data = Article::getArticlesByAuthor($id);

        return $this->render('view', [
            'user' => $user,
            'articles' => $data['articles'],
            'pagination' => $data['pagination'],
        ]);
    }
}
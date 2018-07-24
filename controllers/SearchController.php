<?php
/**
 * Created by PhpStorm.
 * User: lvcheng
 * Date: 18-7-23
 * Time: 下午3:24
 */
namespace app\controllers;
use app\models\content\Article;
use Yii;
use yii\helpers\VarDumper;

class SearchController extends BaseController
{
    public function actionIndex(){
        $keyword = strip_tags(trim(Yii::$app->request->post('keyword')));

        $data = Article::searchArticles($keyword);


        return $this->render('index', [
            'articles'=>$data['articles'],
            'pagination'=> $data['pagination'],
            'keyword' => $keyword
        ]);
    }
}
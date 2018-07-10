<?php
namespace app\controllers;

class ArticleController extends BaseController
{
    //文章详情
    public function actionIndex($id){


        return $this->render('index');
    }
}
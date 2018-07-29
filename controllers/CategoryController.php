<?php
namespace app\controllers;

use app\models\content\Category;
use app\models\content\Topic;
use yii\helpers\VarDumper;
use yii\web\BadRequestHttpException;

class CategoryController extends BaseController
{
    //分类列表
    public function actionIndex(){

        //查询所有的分类数据
        $data = Category::getCategorys();

        return $this->render('index',[
            'data' => $data,
        ]);
    }

    //分类查看
    public function actionView($id){
        $id = (int) $id;
        if($id <= 0)
            throw new BadRequestHttpException('请求参数错误。');

        $category = Category::find()->where(['id'=>$id])->one();
        $data = Topic::getTopicsByCat($category['id']);

        return $this->render('view',[
            'category' => $category,
            'topics' =>$data['topics'],
            'pagination' =>$data['pagination'],
        ]);
    }

}
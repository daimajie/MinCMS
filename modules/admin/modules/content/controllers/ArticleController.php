<?php
namespace app\modules\admin\modules\content\controllers;
use app\models\content\Article;
use app\models\content\ArticleForm;
use app\models\content\SearchArticle;
use app\modules\admin\controllers\BaseController;
use Yii;

class ArticleController extends BaseController
{
    //独立方法
    public function actions()
    {
        return [
            //上传
            'upload' => [
                'class' => 'app\components\actions\UploadAction',
                //剪切尺寸
                'shearSize' => [300, 240],
                //保存子目录
                'subDir' => 'article',
                //是否划分日期目录(默认true)
                //'randDir' => true,
            ]
        ];
    }

    //文章列表
    public function actionIndex(){
        $searchModel = new SearchArticle();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        //下拉框初始数据
        $selectArr = ['' => '按分类搜索'];

        return $this->render('index',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'selectArr' => $selectArr
        ]);
    }

    //文章创建
    public function actionCreate(){
        $model = new ArticleForm();
        $selectArr = ['' => '选择所属话题'];


        return $this->render('create',[
            'model' => $model,
            'selectArr' => $selectArr
        ]);

    }

    //文章查看
    public function actionView($id){
        return $this->render('view');

    }

    //文章编辑
    public function actionUpdate($id){
        return $this->render('create');
    }

    //文章删除
    public function actionDelete($id){

    }

    //文章批量删除
    public function actionBatchDel(){

    }

    //获取模型
    public static function getModel($id){

    }


}
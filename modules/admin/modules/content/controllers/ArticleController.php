<?php
namespace app\modules\admin\modules\content\controllers;
use app\models\content\Article;
use app\models\content\ArticleForm;
use app\models\content\ArticleTag;
use app\models\content\Content;
use app\models\content\SearchArticle;
use app\models\content\Tag;
use app\models\content\Topic;
use app\modules\admin\controllers\BaseController;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

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
        $selectArr = ['' => '按话题搜索'];

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
        $selectTag = [];

        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->store()){
                //创建成功
                Yii::$app->session->setFlash('success', '新建文章成功，等待审核中。');
                return $this->redirect(['index']);
            }
            //新建失败 显示表单及错误

            //保留数据
            $selectArr[$model->topic_id] = Topic::find()->select(['name'])->where(['id'=>$model->topic_id])->scalar();
            $selectTag = Tag::find()->select(['name'])->indexBy('id')->where(['topic_id'=>$model->topic_id])->asArray()->column();

        }


        return $this->render('create',[
            'model' => $model,
            'selectArr' => $selectArr,
            'selectTag' => $selectTag
        ]);

    }

    //文章编辑
    public function actionUpdate($id){
        $model = static::getFormModel($id);

        if (Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->renew()){
                //更新成功
                if($model->checked){
                    Yii::$app->session->setFlash('success', '编辑文章成功。');
                }else
                    Yii::$app->session->setFlash('success', '编辑文章成功，等待审核中。');
                return $this->redirect(['index']);
            }
        }else{
            //保存的数据
            $model->content = Content::find()->select(['content'])->where(['id'=>$model->content_id])->scalar();
            $model->tags = ArticleTag::find()->select('tag_id')->where(['article_id'=>$model->id])->asArray()->column();
        }



        //当前话题 和 话题包含的全部标签
        $selectArr = Topic::find()->select(['name'])->indexBy('id')->where(['id'=>$model->topic_id])->asArray()->column();
        $selectTag = Tag::find()->select(['name'])->indexBy('id')->where(['topic_id'=>$model->topic_id])->asArray()->column();

        return $this->render('create',[
            'model' => $model,
            'selectArr' => $selectArr,
            'selectTag' => $selectTag
        ]);
    }

    //文章查看
    public function actionView($id){
        $model = static::getModel($id);


        return $this->render('view',[
            'model' => $model,
        ]);

    }

    //文章删除
    public function actionDelete($id){
        $model = static::getModel($id);

        try{
            if($model->delteArticle())
                Yii::$app->session->setFlash('success', '删除文章成功。');

        }catch (\Exception $e){
            Yii::$app->session->setFlash('error', $e->getMessage());
        }catch (\Throwable $e){
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);

    }

    //放入回收站
    public function actionPutRecycle($id){
        $model = static::getModel($id);

        $model->recycle = 1;
        if($model->save(false)){
            Yii::$app->session->setFlash('success', '已经成功放入回收站。');

        }else
            Yii::$app->session->setFlash('error', '放入回收站失败，请重试。');
        return $this->redirect(['index']);
    }

    //草稿箱
    public function actionDraft(){
        $searchModel = new SearchArticle();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'draft');

        //下拉框初始数据
        $selectArr = ['' => '按话题搜索'];

        return $this->render('index',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'selectArr' => $selectArr
        ]);
    }

    //回收站
    public function actionRecycle(){
        $searchModel = new SearchArticle();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'recycle');

        //下拉框初始数据
        $selectArr = ['' => '按话题搜索'];

        return $this->render('index',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'selectArr' => $selectArr
        ]);
    }

    //从回收站恢复文章
    public function actionRestore($id){
        $model = static::getModel($id);

        $model->recycle = 0;
        if($model->save(false)){
           Yii::$app->session->setFlash('success', '恢复文章成功。');
           return $this->redirect(['article/index']);
        }else{
            Yii::$app->session->setFlash('error', '恢复文章失败，请重试。');
            return $this->refresh();
        }
    }

    //获取表单模型
    private static function getFormModel($id){
        $id = (int) $id;
        if($id <= 0){
            throw new BadRequestHttpException('请求参数错误。');
        }

        $model = ArticleForm::findOne($id);

        if(!$model){
            throw new NotFoundHttpException('没有相关数据。');
        }

        return $model;
    }

    //获取模型
    private static function getModel($id){
        $id = (int)$id;
        if($id <= 0)
            throw new BadRequestHttpException('请求参数错误。');

        $model = Article::findOne($id);

        if(!$model)
            throw new NotFoundHttpException('没有相关数据。');

        return $model;
    }


}
<?php
namespace app\modules\admin\modules\content\controllers;
use app\models\content\Category;
use app\models\content\SearchCategory;
use app\modules\admin\controllers\BaseController;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * 后台内容控制器
 */
class CategoryController extends BaseController
{
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'app\components\actions\UploadAction',
                //剪切尺寸
                'shearSize' => [300, 240],
            ],
            'search' => [
                'class' => 'app\components\actions\SearchAction',
                'model' => 'app\models\content\Category'
            ]
        ];
    }

    //首页
    public function actionIndex(){
        $searchModel = new SearchCategory();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    //新建
    public function actionCreate(){
        $model = new Category();

        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->save()){
                //创建成功
                Yii::$app->session->setFlash('success','创建分类成功。');
                return $this->redirect(['index']);
            }
            //创建失败显示表单
        }

        return $this->render('create',[
            'model' => $model,
        ]);
    }

    //查看
    public function actionView($id){
        $model = static::getModel($id);

        return $this->render('view',[
            'model' => $model,
        ]);
    }

    //编辑
    public function actionUpdate($id){
        $model = static::getModel($id);

        if(Yii::$app->request->isPost){
            //加载数据 更新图片 保存
            if($model->load(Yii::$app->request->post()) && $model->updateImg() && $model->save()){
                //修改成功
                Yii::$app->session->setFlash('success','修改分类成功。');
                return $this->redirect(['index']);

            }
            //修改失败 显示表单
        }

        return $this->render('create',[
            'model' => $model,
        ]);
    }

    //删除
    public function actionDelete($id){
        $model = static::getModel($id);

        if(Category::isAllowDelete($id)){
            if(Category::deleteImg($model->image) && $model->delete()){
                //删除成功
                Yii::$app->session->setFlash('success', '删除分类成功。');
            }else
                //删除失败
                Yii::$app->session->setFlash('error', '删除分类失败，请重试。');
        }else{
            Yii::$app->session->setFlash('error', '请先删除该分类下所有话题。');
        }

        return $this->redirect(['index']);

    }

    //模型
    private static function getModel($id){
        //参数验证
        $id = (int)$id;
        if($id <= 0)
            throw new BadRequestHttpException('请求错误。');

        //获取模型
        $model = Category::findOne($id);
        if(!$model)
            throw new NotFoundHttpException('没有相关数据。');

        return $model;
    }

    //批量删除
    public function actionBatchDel(){
        $cats_id = Yii::$app->request->post('cats_id');

        //检测参数
        if(empty($cats_id) || !is_array($cats_id)){
            Yii::$app->session->setFlash('error', '请选择要删除的分类。');
            return $this->redirect(['index']);
        }

        //检测是否允许删除
        if(Category::isAllowDelete($cats_id)){
            //获取所有图片信息
            $images = Category::getImgByIds($cats_id);

            //删除图片
            Category::batchDeleteImg($images);

            //删除记录
            if(Category::deleteAll(['in', 'id', $cats_id]) === false){
                Yii::$app->session->setFlash('error', '删除分类信息失败，请重试。');
            }else{
                Yii::$app->session->setFlash('success', '批量删除分类成功。');
            }
        }else{
            Yii::$app->session->setFlash('error', '请先删除这些分类下的所有话题。');
        }


        return $this->redirect(['index']);

    }

    //搜索分类
    /*public function actionSearchCats(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            if(!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方式不被允许。');
            $key  = Yii::$app->request->get('key','');
            $datas = Category::searchCatsByKey($key);
            return [
                'success' => true,
                'results' => $datas
            ];
        }catch (Exception $e){
            //调至首页
            return $this->redirect(['/']);
        }

    }*/




}
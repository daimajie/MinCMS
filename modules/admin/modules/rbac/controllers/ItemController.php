<?php
namespace app\modules\admin\modules\rbac\controllers;
use app\modules\admin\controllers\BaseController;
use app\models\rbac\AuthItem;
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use app\models\rbac\SearchAuthItem;

class ItemController extends BaseController
{
    public function actions()
    {
        return [
            'search' => [
                'class' => 'app\components\actions\SearchAction',
                'model' => 'app\models\rbac\AuthItem'
            ]
        ];
    }

    //列表页
    public function actionIndex()
    {

        $searchModel = new SearchAuthItem();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    //创建**
    public function actionCreate()
    {

        $model = new AuthItem(null);

        if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {

            Yii::$app->session->setFlash('success', '创建条目成功。');

            return $this->redirect(['index']);
        } else {

            return $this->render('create', ['model' => $model]);
        }
    }

    //详情**
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', ['model' => $model]);
    }

    //更新**
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', '编辑条目成功。');
            return $this->redirect(['index']);
        }

        return $this->render('create', ['model' => $model]);
    }

    //删除**
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if(Yii::$app->authManager->remove($model->item)){
            Yii::$app->session->setFlash('success', '删除条目成功。');
        }else
            Yii::$app->session->setFlash('error', '删除条目失败，请重试。');

        return $this->redirect(['index']);
    }

    //指派
    public function actionAssign($id)
    {
        //获取所有提交的条目
        $items = Yii::$app->getRequest()->post('items', []);

        //获取条目实例
        $model = $this->findModel($id);

        //给条目添加子节点
        $success = $model->addChildren($items);

        //获取所有以分配和未分配给当前条目的条目
        var_dump($model->getItems());
    }

    //移除指派
    public function actionRemove($id)
    {
        //获取所有提交的条目
        $items = Yii::$app->getRequest()->post('items', []);

        //获取当前操作的条目
        $model = $this->findModel($id);

        //移除指定的子节点
        $success = $model->removeChildren($items);

        //获取所有分配和为分配的条目
        var_dump($model->getItems());
    }

    //获取模型**
    protected function findModel($id)
    {
        $auth = Yii::$app->authManager;

        $item = null;
        if(($item = $auth->getRole($id)) || ($item = $auth->getPermission($id))){
            return new AuthItem($item);
        }
        throw new NotFoundHttpException('没有相关数据。');
    }



}
<?php
namespace app\modules\admin\modules\rbac\controllers;
use app\models\rbac\AuthRule;
use app\models\rbac\SearchAuthRule;
use app\modules\admin\controllers\BaseController;
use Yii;
use yii\web\NotFoundHttpException;

class RuleController extends BaseController
{
    //规则列表
    public function actionIndex(){
        $searchModel = new SearchAuthRule();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    //添加规则
    public function actionCreate(){

        $model = new AuthRule(null);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', '创建规则成功。');
            return $this->redirect(['index']);
        } else {
            return $this->render('create', ['model' => $model]);
        }
    }

    //修改规则
    public function actionUpdate($id){
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success','编辑规则成功。');
            return $this->redirect(['index']);
        }

        return $this->render('create', ['model' => $model,]);
    }

    //查看规则
    public function actionView($id){
        $model = $this->findModel($id);

        return $this->render('view', ['model' => $model]);
    }

    //删除规则
    public function actionDelete($id){
        $model = $this->findModel($id);
        if(Yii::$app->authManager->remove($model->item)){
            Yii::$app->session->setFlash('success', '删除规则成功。');
        }else
            Yii::$app->session->setFlash('error', '删除规则失败，请重试。');

        return $this->redirect(['index']);
    }


    protected function findModel($id)
    {
        $item = Yii::$app->authManager->getRule($id);
        if ($item) {
            return new AuthRule($item);
        } else {
            throw new NotFoundHttpException('没有相关数据。');
        }
    }


}
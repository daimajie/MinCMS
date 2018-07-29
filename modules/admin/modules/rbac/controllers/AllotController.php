<?php
namespace app\modules\admin\modules\rbac\controllers;
use app\models\rbac\AuthAllot;
use app\modules\admin\controllers\BaseController;
use Yii;
use yii\base\Exception;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class AllotController extends BaseController
{
    //列表**
    public function actionIndex(){
        $dataProvider = new ArrayDataProvider([
            'allModels' => (new Query())
                ->from('{{%auth_item_child}}')
                ->distinct()
                ->groupBy('parent')
                ->select(['parent', 'child'=>'group_concat(child)'])
                ->all(),
        ]);

        return $this->render('index',[
            'dataProvider' => $dataProvider,
        ]);
    }

    //添加**
    public function actionCreate(){
        $model = new AuthAllot();


        if(Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post()) && $model->addChild()) {
                Yii::$app->session->setFlash('success', '分配成功');
                return $this->redirect(['index']);
            }

        }

        $selectArr = [''=>'选择条目'];
        return $this->render('update',[
            'selectArr' => $selectArr,
            'model' => $model,
            'roles' => [],
            'permissions' => [],
        ]);
    }

    //更新
    public function actionUpdate($id){
        $model = $this->findModel($id);

        if(Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post()) && $model->addChild()) {
                Yii::$app->session->setFlash('success', '分配成功');

                $prev = Url::previous('item_index');
                if(!empty($prev)){
                    Url::remember(null, 'item_index');
                    return $this->redirect($prev);
                }

                return $this->redirect(['index']);
            }
        }

        //获取可分配的子节点
        $auth = Yii::$app->authManager;
        $roles = AuthAllot::getOptions($auth->getRoles(), $model->_item, false);
        $permissions = AuthAllot::getOptions($auth->getPermissions(), $model->_item, false);


        $selectArr = [''=>'选择条目'] + [$model->parent => $model->parent];
        return $this->render('update',[
            'model' => $model,
            'selectArr' => $selectArr,
            'roles' => $roles,
            'permissions' => $permissions
        ]);
    }

    //删除关联关系
    public function actionDelete($id){
        $model = $this->findModel($id);

        if(Yii::$app->authManager->removeChildren($model->_item)){
            Yii::$app->session->setFlash('success', '删除关联关系成功。');
        }
        return $this->redirect(['index']);
    }


    //获取可分配的权限和角色**
    public function actionGetRoleAndPermission(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            //判断请求来性
            if(!Yii::$app->request->isAjax){
                throw new MethodNotAllowedHttpException('请求方式不被允许。');
            }

            //获取父节点名称
            $name = Yii::$app->request->post('name','');
            $auth = Yii::$app->authManager;
            $parent = $auth->getRole($name) ? $auth->getRole($name) : $auth->getPermission($name);

            //为空直接返回
            if(empty($parent)){
                throw new NotFoundHttpException('没有相关数据。');
            }

            //获取可分配的子节点
            $roles = AuthAllot::getOptions($auth->getRoles(), $parent);
            $permissions = AuthAllot::getOptions($auth->getPermissions(), $parent);




            return [
                'errno' => 0,
                'data' => [
                    'roles' => $roles,
                    'permissions' => $permissions
                ]
            ];
        }catch (MethodNotAllowedHttpException $e){
            return $this->redirect(['/']);
        }catch (Exception $e){
            return [
                'errno' => 1,
                'message' => $e->getMessage(),
            ];
        }

    }

    //获取模型
    protected function findModel($id)
    {
        $auth = Yii::$app->authManager;

        $item = null;
        if(($item = $auth->getRole($id)) || ($item = $auth->getPermission($id))){
            return new AuthAllot($item);
        }
        throw new NotFoundHttpException('没有相关数据。');
    }
}
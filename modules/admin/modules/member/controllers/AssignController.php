<?php
namespace app\modules\admin\modules\member\controllers;
use app\models\member\User;
use app\models\rbac\AuthAllot;
use app\models\rbac\AuthAssign;
use app\models\rbac\AuthItem;
use app\modules\admin\controllers\BaseController;
use Yii;
use yii\base\Exception;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\BadRequestHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class AssignController extends BaseController
{
    //列表
    public function actionIndex(){
/*        $test = (new Query())
            ->from(['assign'=>'{{%auth_assignment}}'])
            ->leftJoin('{{%user}}','assign.user_id=user.id')
            ->distinct()
            ->groupBy('user_id')
            ->select(['user_id', 'user.username', 'roles'=>'group_concat(item_name)'])
            ->all();
            //->createCommand()->getSql();

        VarDumper::dump($test, 10, 1);die;*/


        $dataProvider = new ArrayDataProvider([
            'allModels' => (new Query())
                ->from(['assign'=>'{{%auth_assignment}}'])
                ->leftJoin('{{%user}}','assign.user_id=user.id')
                ->select(['assign.*','user.username'])
                ->distinct()
                ->groupBy('user_id')
                ->select(['user_id', 'user.username', 'roles'=>'group_concat(item_name)'])
                ->all(),

        ]);

        return $this->render('index',[
            'dataProvider' => $dataProvider,
        ]);
    }

    //创建
    public function actionCreate(){

        $model = new AuthAssign();

        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->save()){
                //指派角色成功
                Yii::$app->session->setFlash('success', '指派角色成功。');
                return $this->redirect(['index']);
            }
        }

        //获取所有权限
        $auth = Yii::$app->authManager;
        $roles = AuthAllot::getOptions($auth->getRoles(), null);
        $selectArr = ['' => '选择一个用户'];
        return $this->render('create',[
            'model' => $model,
            'selectArr' => $selectArr,
            'roles' => $roles,
        ]);
    }

    //删除
    public function actionDelete($id){
        $model = $this->getUser($id);

        if($model->delete()){
            //删除角色成功
            Yii::$app->session->setFlash('success', '删除指派成功。');
        }else
            Yii::$app->session->setFlash('error', '删除指派失败，请重试。');

        return $this->redirect(['index']);
    }


    //编辑指派
    public function actionAssign($id){
        //获取用户
        $user = $this->getUser($id);

        //获取所有权限
        $auth = Yii::$app->authManager;
        $roles = AuthAllot::getOptions($auth->getRoles(), null);
        //$permissions = AuthAllot::getOptions($auth->getPermissions(), null);

        //接受数据
        if(Yii::$app->request->isPost){
            if($user->load(Yii::$app->request->post()) && $user->save()){
                Yii::$app->session->setFlash('success', '指派角色成功。');

                $prev  = Url::previous('user_index');

                if(!empty($prev)){
                    Url::remember(null, 'user_index');
                    return $this->redirect($prev);
                }

                return $this->redirect(['index']);
            }
        }

        //渲染页面
        $selectArr = [$user->id => $user->username];
        return $this->render('create',[
            'model' => $user,
            'selectArr' => $selectArr,
            'roles' => $roles,
            //'permissions' => $permissions,

        ]);
    }

    /*
     * 获取用户模型
     */
    private function getUser($id){
        $id = (int) $id;
        if($id <= 0)
            throw new BadRequestHttpException('请求参数错误。');

        $model = User::findOne($id);
        if(!$model)
            throw new NotFoundHttpException('没有相关数据。');

        return new AuthAssign($model);
    }

    //获取角色及选中的角色
    public function actionGetRoles(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            //判断请求来性
            if(!Yii::$app->request->isAjax){
                throw new MethodNotAllowedHttpException('请求方式不被允许。');
            }

            //获取父节点名称
            $id = Yii::$app->request->post('id','');

            //获取所有权限及用户指派的权限
            $roles = AuthAssign::getRolesbyUser($id);


            return [
                'errno' => 0,
                'data' => $roles
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
}
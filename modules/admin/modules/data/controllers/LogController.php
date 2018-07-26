<?php
/**
 * Created by PhpStorm.
 * User: lvcheng
 * Date: 18-7-26
 * Time: 上午10:44
 */
namespace app\modules\admin\modules\data\controllers;
use app\modules\admin\controllers\BaseController;
use app\modules\admin\modules\data\models\Log;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use Yii;

class LogController extends BaseController
{
    //日志列表
    public function actionIndex(){
        $dataProvider = new ActiveDataProvider([
            'query'=>Log::find()->orderBy(['log_time'=>SORT_DESC]),
            'pagination' => [
                'pageSize' => 5
            ]
        ]);

        return $this->render('index',[
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id){
        $model = Log::findOne($id);
        if ($model === null)
            throw new NotFoundHttpException('没有相关数据。');

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionDeleteAll(){
        Log::deleteAll();
        Yii::$app->session->setFlash('success','删除日志成功。');
        $this->redirect(['log/index']);
    }
}
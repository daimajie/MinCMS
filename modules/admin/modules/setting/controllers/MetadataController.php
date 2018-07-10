<?php
namespace app\modules\admin\modules\setting\controllers;
use app\models\setting\Metadata;
use app\modules\admin\controllers\BaseController;
use Yii;

class MetadataController extends BaseController
{
    //设置元数据
    public function actionSetup(){
        $model = Metadata::findOne(1);


        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->save()){
                //设置成功
                Yii::$app->session->setFlash('success', '设置元数据成功。');
            }else
                Yii::$app->session->setFlash('error', '设置元数据失败，请的重试。');

            return $this->refresh();

        }

        return $this->render('setup',[
            'model' => $model
        ]);
    }
}
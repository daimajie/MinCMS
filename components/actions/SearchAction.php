<?php
namespace app\components\actions;

use yii\base\Action;
use Yii;
use yii\base\Exception;
use yii\web\MethodNotAllowedHttpException;
use yii\web\Response;

class SearchAction extends Action
{
    //保存目录
    public $model;

    /**
     * 通过搜索关键字 搜索相关数据 用于下拉搜索框
     * @return array #下拉框使用数据
     * @throws Exception
     * @throws MethodNotAllowedHttpException
     */
    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        try{
            if(!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方式不被允许。');

            $key  = Yii::$app->request->get('key','');

            $datas = call_user_func($this->model . '::searchByKey', $key);

            return [
                'success' => true,
                'results' => $datas
            ];
        }catch (MethodNotAllowedHttpException $e){

            return $this->controller->redirect(['/']);
        }

    }
}
<?php
namespace app\components\actions;

use yii\base\Action;
use app\models\content\UploadForm;
use Yii;
use yii\base\Exception;
use yii\web\MethodNotAllowedHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

class UploadAction extends Action
{
    //剪切尺寸
    public $shearSize = [];
    //保存目录
    public $subDir = 'category';

    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            //检测请求方法
            if(!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方式不被允许。');

            //创建模型
            $model = new UploadForm();
            $model->imageFile = UploadedFile::getInstanceByName('imageFile');

            //上传
            if(!$model->upload()){
                throw new Exception($model->getFirstError('imageFile'));
            }

            //剪切并删除原图
            if(!$model->shearImg($this->shearSize, $this->subDir)){
                throw new Exception($model->getFirstError('imageFile'));
            }

            //返回数据
            return [
                'errno' => 0,
                'message' => '上传图片成功。',
                'url' =>  $model->saveDir . $model->newName
            ];
        }catch (MethodNotAllowedHttpException $e){

            //跳至首页
            return $this->controller->redirect(['/']);
        }catch (Exception $e){

            //返回错误信息
            return [
                'errno' => 1,
                'message' => $e->getMessage(),
            ];
        }

    }
}
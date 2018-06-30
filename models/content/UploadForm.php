<?php
namespace app\models\content;

use yii\base\Exception;
use yii\base\Model;
use yii\web\UploadedFile;
use Yii;
use yii\helpers\FileHelper;
use yii\imagine\Image;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;  //文件实例
    public $fullPath;   //上船后的文件全路径
    //public $savePath;   //剪切后保存的路径
    public $newName;    //新文件名
    public $saveDir;    //最后保存的目录（$saveDir . $newName 用于数据存储）

    public function rules()
    {
        return [
            [['imageFile'], 'image', 'skipOnEmpty' => false],
            [['imageFile'], 'image',
                'extensions' => 'png, jpg, jpeg, gif',
                'message' => '图片格式不正确。'
            ],
            [['imageFile'], 'image',
                'minWidth' => 300, 'maxWidth' => 1000,
                'minHeight' => 240, 'maxHeight' => 1000,
                'message' => '图片应最小300*240，最大1000*1000像素。'
            ],
        ];
    }

    /**
     * 上传图片
     * @return bool
     */
    public function upload()
    {
        if ($this->validate()) {

            //得到上传路径
            if(!$upPath = $this->getUploadPath()){
                $this->addError('imageFile', '上传目录创建失败。');
                return false;
            }

            //生成文件名
            $this->newName = $newName = static::generateFileName($this->imageFile->extension);

            //文件全路径
            $this->fullPath = $fullPath = $upPath . $newName;

            //上传
            $this->imageFile->saveAs($fullPath);
            return true;
        } else {
            return false;
        }

    }

    /**
     * 生成新文件名
     * @param $ext string #文件后缀
     * @return string
     */
    private static function generateFileName($ext){
        $timestamp = str_replace('.', '',microtime(true));
        return date('His-') . $timestamp . '.' . $ext;
    }

    /**
     * 检测上传路径是否存在
     * @param $dir $string #上传目录，默认临时目录
     * @param $subDir $string #是否创建随机子目录
     * @return bool|string #成功返回上传路径失败返回false
     */
    private function getUploadPath($dir='tempPath', $subDir=false){
        //上传目录是否合法
        if(!in_array($dir, Yii::$app->params['imgPath']['allowPath'])){
            return false;
        }

        //真是保存目录，用户记录数据
        $saveDir = $dir . '/';

        //是否创建随机子目录
        if($subDir){
            $randDir = date('Ymd');
            $saveDir .= $randDir . '/';
        }

        //获取上传路径
        $tempPath = Yii::$app->params['imgPath']['imgUp'] . '/' . $saveDir;
        $this->saveDir = $saveDir;

        //检测目录是否存在
        if(!file_exists($tempPath) || !is_dir($tempPath)){
            try{
                FileHelper::createDirectory($tempPath,0775, true);
            }catch (Exception $e){
                return false;
            }
        }
        return $tempPath;
    }

    /**
     * 剪切图片并删除原图片。
     * @param $size array #剪切尺寸【1：width, 2:height】
     * @param $dir string #分类目录 如：category，topic，article
     * @return string #图片url地址
     */
    public function shearImg($size, $dir){

        if(empty($size) || !is_array($size) || count($size) !== 2){
            $this->addError('imageFile', '参数错误。');
            return false;
        }

        list($width, $height) = [(int)$size[0],(int)$size[1]];
        if($width <= 0 || $height <= 0){
            $this->addError('imageFile', '剪切尺寸无效。');
            return false;
        }

        //得到保存路径
        if(!$savePath = $this->getUploadPath($dir, true)){
            $this->addError('imageFile', '保存目录创建失败。');
            return false;
        }

        //剪切
        Image::thumbnail($this->fullPath,$width, $height)
            ->save($savePath . $this->newName, ['jpeg_quality' => 50]);


        //删除原图
        FileHelper::unlink($this->fullPath);


        //剪切成功
        return true;


    }
}
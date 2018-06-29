<?php

namespace app\models\content;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "{{%category}}".
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%category}}';
    }

    //自动完成
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    //绑定事件(修改分类后如果图片有变化删除旧图片，删除分类时删除图片)
    /*public function init()
    {
        parent::init();
        $this->on(self::EVENT_BEFORE_UPDATE, 'updateImg');
        $this->on(self::EVENT_BEFORE_DELETE, 'deleteImg');
    }*/

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name','desc','image'], 'required'],
            //[['count', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 18],
            [['name'], 'unique', 'message' => '该分类已经存在。'],
            [['desc'], 'string', 'max' => 255],
            [['image'], 'string', 'max' => 125],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'name' => '分类名',
            'desc' => '描述',
            'image' => '图片',
            'count' => '话题总数',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * 删除旧有图片
     */
    public function updateImg(){
        //新纪录不存在更新图片操作
        if ($this->isNewRecord){
            return true;
        }

        //如果图片没有改动
        if($this->getOldAttribute('image') === $this->image){
            return true;
        }

        /*
         * 图片有改动 删除旧图片
         */
        if(static::deleteImg($this->getOldAttribute('image')))
            return true;
        return false;

    }

    /**
     * 删除制定图片根据给定的图片记录
     * @param $image string #图片记录
     * @return bool
     */
     public static function deleteImg($image){
         //获取图片上传路径
         $upPath = Yii::$app->params['imgPath']['imgUp'] . '/';

         //直接删除
         @FileHelper::unlink($upPath . $image);

         //返回
         return true;
     }

    /**
     * 批量删除图片
     * @param $images array #图片记录集合
     * @return bool
     */
     public static function batchDeleteImg($images){
         //检测参数
         if(!is_array($images))
            return false;

         foreach ($images as $image){
             static::deleteImg($image);
         }
         return true;
     }


    /**
     * 根据id集获取所有关联图片信息
     * @param $cats_id array #分类id集合
     * @return array
     */
     public static function getImgByIds($cats_id){
         return static::find()
             ->select(['image'])
             ->where(['in', 'id', $cats_id])
             ->asArray()
             ->column();
     }
}

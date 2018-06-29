<?php

namespace app\models\content;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "{{%topic}}".
 */
class Topic extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%topic}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name','image','category_id','desc'], 'required'],
            [['finished', 'category_id'], 'integer'],
            [['finished'], 'default', 'value'=>0],
            [['category_id'], 'exist', 'targetClass'=>'app\models\content\Category', 'targetAttribute' => 'id'],

            [['name'], 'string', 'max' => 18],
            [['desc'], 'string', 'max' => 255],
            [['image'], 'string', 'max' => 125],
        ];
    }

    //自动完成 创建者 和时间戳
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'user_id',
                'updatedByAttribute' => false
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'name' => '话题名',
            'desc' => '描述',
            'image' => '图片',
            'count' => '文章总数',
            'finished' => '是否完结',
            'category_id' => '所属分类',
            'user_id' => '创建者',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    //关联分类
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id'])
            ->select(['id','name']);
    }


    /**
     * 编辑话题后删除原有图片
     * @return bool
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
     * 删除指定图片根据给定的图片记录
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
     * @param $topics_id array #话题id集合
     * @return array
     */
    public static function getImgByIds($topics_id){
        return static::find()
            ->select(['image'])
            ->where(['in', 'id', $topics_id])
            ->asArray()
            ->column();
    }


}
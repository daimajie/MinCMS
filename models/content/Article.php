<?php

namespace app\models\content;

use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use Yii;

/**
 * This is the model class for table "{{%article}}".
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%article}}';
    }

    //auto complete
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
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
    public function rules()
    {
        return [
            [['type', 'words', 'recommend', 'checked', 'draft', 'recycle', 'visited', 'comment', 'likes', 'collect', 'topic_id', 'content_id', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 125],
            [['brief'], 'string', 'max' => 512],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'title' => '文章标题',
            'brief' => '文章简介',
            'image' => '文章图片',
            'type' => '文章类型',
            'words' => '字数',
            'recommend' => '推荐阅读',
            'checked' => '设查',
            'draft' => '草稿箱',
            'recycle' => '回收站',
            'visited' => '浏览次数',
            'comment' => '评论次数',
            'likes' => '喜欢次数',
            'collect' => '收藏次数',
            'topic_id' => '所属话题',
            'content_id' => '文章内容',
            'user_id' => '作者',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}

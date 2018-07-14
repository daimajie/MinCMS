<?php

namespace app\models\collect;

use app\models\content\Article;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%collect}}".
 *
 * @property int $id 主键
 * @property int $user_id 用户
 * @property int $article_id 文章
 * @property int $type 类型
 * @property int $created_at 创建时间
 */
class Collect extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%collect}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['article_id', 'type'], 'require'],
            [['article_id', 'type'], 'integer'],
            [['article_id'], 'exist', 'targetClass' => Article::class,'targetAttribute' => 'id'],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
            ],
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'user_id',
                'updatedByAttribute' => false
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'user_id' => '用户',
            'article_id' => '文章',
            'type' => '类型',
            'created_at' => '创建时间',
        ];
    }

    /**
     * 获取当前用户是否已经喜欢或收藏过该文章
     * @param $article_id int #文章id
     * @return array
     */
    public static function isCollect($article_id){
        if(Yii::$app->user->isGuest)
            return ['collect'=>false, 'likes'=>false];

        $user_id = Yii::$app->user->id;
        $collect = static::find()
            ->where(['and', ['user_id'=>$user_id], ['article_id'=>$article_id]])
            ->andWhere(['type'=>1])
            ->count();
        $likes = static::find()
            ->where(['and', ['user_id'=>$user_id], ['article_id'=>$article_id]])
            ->andWhere(['type'=>0])
            ->count();
        return [
            'collect' => $collect,
            'likes' => $likes
        ];
    }
}

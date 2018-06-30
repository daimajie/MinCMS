<?php

namespace app\models\content;

use Yii;

/**
 * This is the model class for table "{{%article_tag}}".
 *
 * @property int $article_id 文章ID
 * @property int $tag_id 标签ID
 */
class ArticleTag extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%article_tag}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['article_id', 'tag_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'article_id' => '文章ID',
            'tag_id' => '标签ID',
        ];
    }
}

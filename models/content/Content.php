<?php

namespace app\models\content;

use Yii;

/**
 * This is the model class for table "{{%content}}".
 *
 * @property int $id 主键
 * @property string $content 文章年内容 
 */
class Content extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%content}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'content' => '文章内容 ',
        ];
    }
}

<?php

namespace app\models\setting;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%metadata}}".
 *
 * @property int $id 主键
 * @property string $name 站点名称
 * @property string $keywords 关键字
 * @property string $description 站点描述
 * @property int $updated_at 修改时间
 */
class Metadata extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%metadata}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 18],
            [['keywords'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 512],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'name' => '站点名称',
            'keywords' => '关键字',
            'description' => '站点描述',
            'updated_at' => '修改时间',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class'=>TimestampBehavior::class,
                'createdAtAttribute'=>false,
            ],
        ];
    }
}

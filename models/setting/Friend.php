<?php

namespace app\models\setting;

use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%friend}}".
 *
 * @property int $id 主键
 * @property string $site 站点名称
 * @property string $url 站点地址
 * @property int $sort 排序
 */
class Friend extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%friend}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sort'], 'integer', 'max' => 150, 'min'=>0],
            [['site'], 'string', 'max' => 18],
            [['site'], 'unique'],
            [['url'], 'string', 'max' => 64],
            [['url'], 'url', 'defaultScheme' => 'http'],
            [['sort'], 'default', 'value' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'site' => '站点名称',
            'url' => '站点地址',
            'sort' => '排序',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}

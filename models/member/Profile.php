<?php

namespace app\models\member;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "{{%profile}}".
 */
class Profile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%profile}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['realname', 'address','sign','blog'], 'required'],
            [['realname'], 'string', 'max' => 18],
            [['address'], 'string', 'max' => 32],
            [['sign'], 'string', 'max' => 225],
            [['blog'], 'string', 'max' => 125],

            [['blog'], 'url', 'defaultScheme' => 'http'],
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
            'realname' => '真实姓名',
            'address' => '位置',
            'sign' => '签名',
            'blog' => '博客',
            'user_id' => '用户',
            'created_at' => '创建时间',
        ];
    }
}

<?php

namespace app\models\member;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%user}}".
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    //auto complete
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    /*public function rules()
    {
        return [
            [['auth_key'], 'required'],
            [['profile_id', 'role_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username'], 'string', 'max' => 18],
            [['email', 'image'], 'string', 'max' => 64],
            [['auth_key', 'password_hash', 'password_reset_token', 'qqopenid', 'wxopenid'], 'string', 'max' => 255],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
            [['qqopenid'], 'unique'],
            [['wxopenid'], 'unique'],
        ];
    }*/

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'username' => '用户名',
            'email' => '邮箱',
            'image' => '头像',
            'auth_key' => 'Auth_Key',
            'password_hash' => '密码',
            'password_reset_token' => '密码重置key',
            'qqopenid' => 'qq登陆',
            'wxopenid' => 'wx登陆',
            'profile_id' => '用户详情',
            'group' => '群组',
            'count' => '文章数',
            'created_at' => '注册时间',
            'updated_at' => '修改时间',
            'lasttime' => '最后登录时间',
            'signip' => '登录ip'
        ];
    }


    //设置密码
    public function generatePasswordHash($password){
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    //设置auth_key
    public function generateAuthKey(){
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    //检测密码
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }




    /**
     * 根据用户ID获取用户实例
     */
    public static function findIdentity($id){}

    /**
     * 根据accsstoken获取用户实例（用户app应用）
     */
    public static function findIdentityByAccessToken($token, $type = null){}

    /**
     * 获取当前用户ID
     */
    public function getId(){}

    /**
     * 获取当前用户auth_key (用户面登录场景)
     */
    public function getAuthKey(){}

    /**
     * 验证auth_key（通过给予登录）
     */
    public function validateAuthKey($authKey){}




}

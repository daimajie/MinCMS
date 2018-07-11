<?php
namespace app\models\member;
use yii\base\InvalidArgumentException;
use yii\base\Model;

class ResetPasswordForm extends Model
{
    public $username;
    public $password;
    public $re_password;

    private $_user;

    public function __construct($token, array $config = [])
    {
        //检测数据
        if (empty($token) || !is_string($token)) {
            throw new InvalidArgumentException('传递参数错误.');
        }

        //获取用户实例
        $this->_user = User::findByPasswordResetToken($token);

        if (!$this->_user) {
            throw new InvalidArgumentException('连接已失效，请重新发送邮件。.');
        }

        $this->username = $this->_user->username;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['password', 're_password'], 'required'],
            [['password'], 'string', 'min' => 6],
            [['re_password'], 'compare', 'compareAttribute' => 'password'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => '新密码',
            're_password' => '重复密码',
            'username' => '用户名'
        ];
    }

    public function reset()
    {
        //验证数据
        if(!$this->validate())
            return false;


        $user = $this->_user;

        $user->generatePasswordHash($this->password);
        $user->removePasswordResetToken();
        return $user->save(false);
    }
}
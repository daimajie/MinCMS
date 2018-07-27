<?php

namespace app\models\member;
use Yii;
use yii\base\Model;
use yii\helpers\VarDumper;
use yii\captcha\CaptchaAction;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $captcha;
    public $rememberMe = true;

    private $_user = false;

    public function init()
    {
        parent::init();
        Yii::$app->user->on(yii\web\User::EVENT_AFTER_LOGIN, [$this, 'onAfterLogin']);
    }
    public function onAfterLogin($event){
        $identity = $event->identity;

        User::updateAll(['lasttime'=>time()], ['id'=>$identity->id]);
    }


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'password','captcha'], 'required'],
            [['captcha'], 'captcha','captchaAction' => 'index/captcha'],
            [['rememberMe'], 'boolean'],
            [['password'], 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
            'captcha' =>'验证码',
            'rememberMe' => '记住我(一周免登录)'
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '用户名和密码错误,请重试.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            //7天免登录
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*7 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsernameOrEmail($this->username);
        }

        return $this->_user;
    }
}

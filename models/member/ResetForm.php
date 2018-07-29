<?php
namespace app\models\member;

use yii\base\Model;
use Yii;

class ResetForm extends Model{

    const RESET_PASSWD = 'reset-passwd';
    const RESET_EMAIL = 'reset-email';

    public $password;
    public $new_password;
    public $re_password;
    public $email;
    public $captcha;

    private $_user = false;

    public function rules()
    {
        return [
            //设置密码
            [['password','new_password','re_password'], 'required','on'=>[self::RESET_PASSWD]],
            [['new_password'],'string','min'=>6,'max'=>18,'on'=>[self::RESET_PASSWD]],
            [['re_password'], 'compare', 'compareAttribute' => 'new_password','on'=>[self::RESET_PASSWD]],
            [['password'], 'checkPassword','on'=>[self::RESET_PASSWD]],

            //设置邮箱
            [['email','captcha'], 'required','on'=>[self::RESET_EMAIL]],
            [['email'], 'email','on'=>[self::RESET_EMAIL]],
            [['email'], 'unique', 'targetClass' => User::class],
            [['captcha'], 'checkCaptcha','on'=>[self::RESET_EMAIL]]
        ];
    }

    public function checkCaptcha($attr){
        if(!$this->hasErrors()){
            //获取session验证码数据
            $session = Yii::$app->session;
            $data = $session->get('email-captcha','');
            if (empty($data)){
                $this->addError($attr, '数据验证失败，请重试。');
                return;
            }

            //判断时间
            if((time() - $data['lifetime']) > $data['start_at']){
                $this->addError($attr, '验证码有效时间已过，请重新发送');
                return;
            }

            //判断是否一致
            if($data['captcha'] !== $this->captcha){
                $this->addError($attr, '验证码填写不正确，请查证。');
                return;
            }

            //清空captcha
            $session->remove('email-captcha');
        }
    }

    public function checkPassword($attr){
        if(!$this->hasErrors()){
            if($this->password == $this->new_password){
                $this->addError('new_password', '新密码不能与原始密码一致。');
            }

            $user = $this->getUser();
            if(!$user || !$user->validatePassword($this->password)){
                $this->addError($attr, '原始密码错误,请重试.');
            }
        }
    }

    public function attributeLabels()
    {
        return [
            'password' => '原始密码',
            'new_password' => '新密码',
            're_password' => '重复密码',

            'email' => '邮箱地址',
            'captcha' => '验证码'
        ];
    }

    /**
     * 设置密码
     */
    public function setPasswd(){
        if(!$this->validate())
            return false;

        //写入数据
        $user = $this->getUser();
        $user->generatePasswordHash($this->new_password);
        return $user->save(false);
    }


    private function getUser(){
        if ($this->_user === false) {
            $this->_user = User::findOne(Yii::$app->user->id);

        }

        return $this->_user;
    }

    /**
     * 设置邮箱
     */
    public function setEmail(){
        if(!$this->validate()){
            return false;
        }

        //写入数据
        $user = $this->getUser();
        $user->email = $this->email;
        return $user->save(false);

    }


}
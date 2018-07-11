<?php
namespace app\models\member;
use yii\base\Model;
use Yii;

class RegisterForm extends Model
{
    //属性
    public $username;
    public $password;
    public $re_password;
    public $email;
    public $captcha;


    public function rules()
    {
        return [
            [['username', 'password', 'email', 'captcha'], 'required'],
            [['captcha'], 'checkEmailCaptcha'],
            [['username'], 'string', 'max' => 18],
            [['email'], 'string', 'max' => 32],
            [['password'], 'string', 'min' => 6, 'max' => 18],
            [['re_password'], 'compare', 'compareAttribute' => 'password'],
            [['username', 'email'], 'unique', 'targetClass' => User::class],
        ];
    }

    public function checkEmailCaptcha($attr){
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

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
            're_password' =>'重复密码',
            'email' => '邮箱',
            'captcha' => '邮箱验证码'
        ];
    }

    public function register(){
        if(!$this->validate()){
            return false;
        }

        $user = new User();

        $user->username = $this->username;
        $user->email = $this->email;
        $user->generatePasswordHash($this->password);
        $user->generateAuthKey();

        //保存
        return $user->save(false);


    }

}
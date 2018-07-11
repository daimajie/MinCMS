<?php
namespace app\models\member;
use app\components\helper\Helper;
use yii\base\Model;
use Yii;

class ForgetForm extends Model
{
    //属性
    public $username;
    public $email;
    public $_user = null;

    public function rules()
    {
        return [
            [['email','username'], 'required'],
            [['email','username'], 'trim'],
            [['username'], 'string','max'=>18],
            [['email'], 'email'],
            [['username'],'checkUsername'],
        ];
    }

    /**
     * 检测用户名与邮箱是否匹配
     */
    public function checkUsername($attribute, $params){
        if(!$this->hasErrors()){
            $user = $this->getUser();
            if(!$user)
                $this->addError($attribute, '用户名与邮箱不匹配。');
        }
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'email' => '验证邮箱'
        ];
    }

    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsernameAndEmail($this->username,$this->email);
        }
        return $this->_user;
    }

    public function sendEmail(){
        if(!$this->validate())
            return false;

        $user = $this->getUser();
        if (!$user) {
            return false;
        }

        /*确保密码重置token的存在*/
        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        /*发送邮件*/
        $view = 'message/password-reset-token';
        $subject = Yii::$app->name . ' - ' . '找回密码连接';
        $ret = Helper::sendEmail(
            Yii::$app->params['adminEmail'],  //from
            $user->email,                     //to
            $subject,                         //subject
            $view,                            //view
            [                                 //variable
                'user'=>$user,
                'title' => $subject
            ]
        );
        return $ret;

    }
}
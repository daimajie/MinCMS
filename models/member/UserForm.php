<?php
namespace app\models\member;
use Yii;
use yii\helpers\FileHelper;

class UserForm extends User{

    //场景声明
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    public $password;
    public $re_password;
    public $reset_image;


    //规则
    public function rules()
    {
        return [
            [['username','email','group'], 'required', 'on'=>[static::SCENARIO_CREATE,static::SCENARIO_UPDATE]],
            [['password','re_password'], 'required', 'on'=>[static::SCENARIO_CREATE]],
            [['username'], 'string', 'min'=>5, 'max'=>18, 'on'=>[static::SCENARIO_CREATE,static::SCENARIO_UPDATE]],
            [['email'], 'email', 'on'=>[static::SCENARIO_CREATE,static::SCENARIO_UPDATE]],
            [['password'], 'string', 'min'=>6, 'max'=>12, 'on'=>[static::SCENARIO_CREATE,static::SCENARIO_UPDATE]],
            [['re_password'], 'compare', 'compareAttribute' => 'password', 'on'=>[static::SCENARIO_CREATE,static::SCENARIO_UPDATE]],
            [['group'], 'integer', 'on'=>[static::SCENARIO_CREATE,static::SCENARIO_UPDATE]],

            [['reset_image'], 'safe', 'on'=>[static::SCENARIO_UPDATE]]
        ];
    }

    public function attributeLabels()
    {
        $parent = parent::attributeLabels();
        return $parent + [
                'password' => '密码',
                're_password' => '重复密码',
                'reset_image' => '置空头像'
            ];
    }

    public function store(){
        if (!$this->validate()){
            return false;
        }

        //生成auth_key
        $this->generateAuthKey();

        //生成密码
        $this->generatePasswordHash($this->password);

        //保存数据
        return $this->save(false);
    }

    public function renew(){
        if(!$this->validate())
            return false;

        //如果修改密码
        if(!empty($this->password)){
            if(!$this->validatePassword($this->password))
                $this->generatePasswordHash($this->password);
            else{
                $this->addError('password', '不能和原密码一致，请重新输入。');
                return false;
            }
        }

        //是否重置头像
        if(!empty($this->reset_image) && !empty($this->image)){
            $this->image = '';
        }

        //保存数据
        return $this->save(false);
    }

    public static function deleteImage($image){
        //获取图片上传路径
        $upPath = Yii::$app->params['imgPath']['imgUp'] . '/';

        //直接删除
        @FileHelper::unlink($upPath . $image);

        //返回
        return true;
    }

}
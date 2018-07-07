<?php
namespace app\models\rbac;
use yii\base\Model;
use Yii;
use yii\helpers\VarDumper;
use yii\web\BadRequestHttpException;
use yii\web\IdentityInterface;

class AuthAssign extends Model
{
    public $id;
    public $username;
    public $roles;
    //public $permissions;

    public $user;

    public function rules()
    {
        return [
            [['username','id'], 'required'],
            [['roles'], 'safe'],
            [['id'], 'integer']
        ];
    }

    public function __construct(IdentityInterface $user=null, array $config = [])
    {
        $this->user = $user;

        if(!is_null($user)){
            $this->id = $user->id;
            $this->username = $user->username;
            $this->roles = $this->simplyRoles(Yii::$app->authManager->getRolesByUser($user->id));
            //$this->roles = Yii::$app->authManager->getPermissionsByUser($user->id);
        }

        parent::__construct($config);
    }

    public function attributeLabels()
    {
        return [
            'id' => '用户id',
            'username' => '用户名',
            'roles' => '角色',
            'permissions' => '权限'
        ];
    }

    //权限数据转为数组格式
    public function simplyRoles($items){
        if(empty($items))
            return [];

        $ret = [];
        foreach($items as $item){
            $ret[$item->name] = $item->description;
        }
        return $ret;
    }

    //保存数据
    public function save(){
        if(!$this->validate()){
            return false;
        }
        if(empty($this->roles))
            return true;

        //保存数据
        $trans = Yii::$app->db->beginTransaction();
        try {
            $auth = Yii::$app->authManager;
            //先清空当前用户所有指派
            $auth->revokeAll($this->id);
            foreach ($this->roles as $role) {
                $obj = $auth->getRole($role);
                $auth->assign($obj, $this->id);
            }
            $trans->commit();
        } catch (\Exception $e) {
            $trans->rollback();
            $this->addError('username', $e->getMessage());
            return false;
        }
        return true;
    }

    /**
     * 删除指派
     */
    public function delete(){
        $auth = Yii::$app->authManager;
        if($auth->revokeAll($this->id)){
            return true;
        }
        return false;
    }

    /**
     * 获取所有的权限 以及 指派过的权限
     */
    public static function getRolesbyUser($user_id){
        if($user_id <= 0)
            throw new BadRequestHttpException('请求参数错误。');

        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();

        $oArr = $auth->getRolesByUser($user_id);
        $arr = [];
        foreach($oArr as $item){
            $arr[] = $item->name;
        }

        $items = [];
        foreach ($roles as $role){
            $items[$role->name]['value'] = $role->name;

            $flag = in_array($role->name, $arr);
            $items[$role->name]['selected'] = $flag;
        }

        return $items;
    }
}
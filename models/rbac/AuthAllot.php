<?php
namespace app\models\rbac;
use Yii;
use yii\db\ActiveRecord;

class AuthAllot extends ActiveRecord
{
    const TYPE_ROLE = 1;        //角色
    const TYPE_PERMISSION = 2;  //权限


    public $parent;
    public $child;

    public $_item;

    public function __construct($item = null, array $config = [])
    {
        $this->_item = $item;

        if(!is_null($item)){
            $this->parent = $item->name;
            $children = Yii::$app->authManager->getChildren($item->name);
            $this->child = $this->detachChild($children);

        }

        parent::__construct($config);
    }

    /**
     * 把子节点划分为角色和权限
     * @param $children
     * @return array
     */
    private function detachChild($children){
        if(empty($children))return [];
        $roles = [];
        $permissions = [];
        foreach ($children as $child){
            if($child->type == self::TYPE_ROLE){
                $roles[] = $child->name;
            }else{
                $permissions[] = $child->name;
            }
        }
        return [
            'roles' => $roles,
            'permissions' => $permissions
        ];
    }




    //规则
    public function rules()
    {
        return [
            [['parent'], 'required'],
            [['child'], 'checkEmpty'],
        ];
    }

    //判断子节点是否为空
    public function checkEmpty($attr){
        if(!$this->hasErrors()){
            if(empty($this->$attr)){
                $this->addError('item', '请选择子节点条目');
                return;
            }
        }
    }

    //标签
    public function attributeLabels()
    {
        return [
            'parent' => '父节点',
            'child' => '子节点',
        ];
    }

    //获取可分配节点
    /*public static function getOptions($data, $parent){
        $return = [];
        $auth = Yii::$app->authManager;
        foreach ($data as $obj) {
            if (is_null($parent)) {
                $return[$obj->name] = $obj->description;
                break;
            }
            if (!empty($parent) && $parent->name != $obj->name && $auth->canAddChild($parent, $obj)) {
                $return[$obj->name] = $obj->description;
            }
        }
        return $return;
    }*/
    public static function getOptions($data, $parent,$flag=true){
        $return = [];
        $auth = Yii::$app->authManager;
        foreach ($data as $obj) {
            if (is_null($parent)) {
                if($flag){
                    $return[$obj->name][$obj->name] = $obj->description;
                }else
                    $return[$obj->name] = $obj->description;

                break;
            }
            if (!empty($parent) && $parent->name != $obj->name && $auth->canAddChild($parent, $obj)) {
                if($flag){
                    $return[$obj->name] = [
                        $obj->name => $obj->description,
                        'checked' => $auth->hasChild($parent, $obj)
                    ];
                }else
                    $return[$obj->name] = $obj->description;

            }
        }
        return $return;
    }

    //添加子条目
    public function addChild(){

        //如果没有错误
        if($this->hasErrors()){
            return false;
        }

        //获取父节点
        $auth = Yii::$app->authManager;
        $parent = $auth->getRole($this->parent) ? $auth->getRole($this->parent) :$auth->getPermission($this->parent);

        //父节点错误直接返回
        if (empty($parent)) {
            return false;
        }

        //开启事务
        $trans = Yii::$app->db->beginTransaction();
        try {
            //先清空所有子节点
            $auth->removeChildren($parent);
            foreach ($this->child as $name) {
                $item = $auth->getRole($name) ? $auth->getRole($name) : $auth->getPermission($name);
                $auth->addChild($parent, $item);
            }
            $trans->commit();
        } catch(\Exception $e) {
            $trans->rollback();
            $this->addError('parent', $e->getMessage());
            return false;
        }
        return true;

    }
}
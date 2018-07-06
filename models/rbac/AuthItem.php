<?php
namespace app\models\rbac;
use yii\base\Model;
use Yii;
use yii\db\Query;
use yii\helpers\Json;
use yii\rbac\Item;

class AuthItem extends Model
{
    public $name;
    public $type;
    public $description;
    public $ruleName;
    public $data;

    private $_item;

    const TBL_NAME = '{{auth_item}}';

    public function __construct($item = null, $config = [])
    {
        $this->_item = $item;

        if ($item !== null) {
            $this->name = $item->name;
            $this->type = $item->type;
            $this->description = $item->description;
            $this->ruleName = $item->ruleName;
            $this->data = $item->data === null ? null : Json::encode($item->data);
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['ruleName'], 'checkRule'],
            [['name', 'type'], 'required'],
            [['name'], 'checkUnique', 'when' => function () {
                return $this->isNewRecord || ($this->_item->name != $this->name);
            }],
            [['type'], 'integer'],
            [['description', 'data', 'ruleName'], 'default'],
            [['name'], 'string', 'max' => 64],
        ];
    }


    //检测权限名称是否唯一
    public function checkUnique()
    {
        $authManager = Yii::$app->authManager;
        $value = $this->name;

        //判断是否存在角色或权限
        if ($authManager->getRole($value) !== null || $authManager->getPermission($value) !== null) {
            $this->addError('name', '该名称已经存在。');
            return;
        }
    }

    //检测规则是否存在
    public function checkRule()
    {
        $name = $this->ruleName;
        //如果规则不存在
        if (!Yii::$app->authManager->getRule($name)) {
            //试图根据给的规则数据创建规则
            try {
                $rule = Yii::createObject($name);
                if ($rule instanceof \yii\rbac\Rule) {
                    //创建成功
                    $rule->name = $name;
                    Yii::$app->authManager->add($rule);
                } else {
                    //不是一个正确的规则数据
                    $this->addError('ruleName', '请指定正确的规则名。');
                }
            } catch (\Exception $exc) {
                //不是类名 无法创建对象
                $this->addError('ruleName', '请指定正确的规则名。');
            }
        }
    }
    public function attributeLabels()
    {
        return [
            'name' => '权限名或路由ID',
            'type' => '条目类型',
            'description' => '条目描述',
            'ruleName' => '规则名',
            'data' => '数据',
        ];
    }



    //判断是否是一个新的条目
    public function getIsNewRecord()
    {
        return $this->_item === null;
    }


    //根据id获取一个角色
    public static function find($id)
    {
        $item = Yii::$app->authManager->getRole($id);
        if ($item !== null) {
            return new self($item);
        }

        return null;
    }

    //新建条目
    public function save()
    {
        if ($this->validate()) {
            $manager = Yii::$app->authManager;
            if ($this->_item === null) {
                if ($this->type == Item::TYPE_ROLE) {
                    $this->_item = $manager->createRole($this->name);
                } else {
                    $this->_item = $manager->createPermission($this->name);
                }
                $isNew = true;
            } else {
                $isNew = false;
                $oldName = $this->_item->name;
            }
            $this->_item->name = $this->name;
            $this->_item->description = $this->description;
            $this->_item->ruleName = $this->ruleName;
            $this->_item->data = $this->data === null || $this->data === '' ? null : Json::decode($this->data);
            if ($isNew) {
                $manager->add($this->_item);
            } else {
                $manager->update($oldName, $this->_item);
            }
            return true;
        } else {
            return false;
        }
    }

    //添加关系
    public function addChildren($items)
    {
        $manager = Yii::$app->authManager;
        $success = 0;
        if ($this->_item) {
            foreach ($items as $name) {
                //只能给角色分类权限不能给角色分配角色
                $child = $manager->getPermission($name);
                if ($this->type == Item::TYPE_ROLE && $child === null) {
                    $child = $manager->getRole($name);
                }
                try {
                    $manager->addChild($this->_item, $child);
                    $success++;
                } catch (\Exception $exc) {
                    Yii::error($exc->getMessage(), __METHOD__);
                }
            }
        }
        return $success;
    }

    //删除关系
    public function removeChildren($items)
    {
        $manager = Yii::$app->authManager;
        $success = 0;
        if ($this->_item !== null) {
            foreach ($items as $name) {
                $child = $manager->getPermission($name);
                if ($this->type == Item::TYPE_ROLE && $child === null) {
                    $child = $manager->getRole($name);
                }
                try {
                    $manager->removeChild($this->_item, $child);
                    $success++;
                } catch (\Exception $exc) {
                    Yii::error($exc->getMessage(), __METHOD__);
                }
            }
        }
        return $success;
    }

    //获取当前条目所有可分配 和 不可分配条目
    public function getItems()
    {
        $manager = Yii::$app->authManager;

        //获取所有条目
        $available = [];
        if ($this->type == Item::TYPE_ROLE) {
            foreach (array_keys($manager->getRoles()) as $name) {
                $available[$name] = 'role';
            }
        }
        foreach (array_keys($manager->getPermissions()) as $name) {
            $available[$name] = /*$name[0] == '/' ? 'route' :*/ 'permission';
        }


        //获取当前条目所有子节点
        $assigned = [];
        foreach ($manager->getChildren($this->_item->name) as $item) {
            //$assigned[$item->name] = $item->type == 1 ? 'role' : ($item->name[0] == '/' ? 'route' : 'permission');
            $assigned[$item->name] = $item->type == 1 ? 'role' :  'permission';

            //删除可用信息
            unset($available[$item->name]);
        }

        //删除自己
        unset($available[$this->name]);
        return [
            'available' => $available,
            'assigned' => $assigned,
        ];
    }

    //获取当前条目实例
    public function getItem()
    {
        return $this->_item;
    }

    //获取条目类型
    public static function getTypeName($type = null)
    {
        $result = [
            Item::TYPE_PERMISSION => 'Permission',
            Item::TYPE_ROLE => 'Role',
        ];
        if ($type === null) {
            return $result;
        }

        return $result[$type];
    }

    /**
     * 根据关键字搜索分类 返回下拉列表相关格式数据 最多10条数据
     * @param $key string #关键字
     * @return array #分类数据
     */
    public static function searchByKey($key){

        //获取所有条目
        $query = (new Query())->from(self::TBL_NAME)->select([
            'name'=>'description',
            'value'=>'name',
            'text'=>'description'
        ]);

        if (!empty($key)){
            $query->andWhere(['like', 'name', $key]);
        }

        return $query->orderBy(['created_at'=>SORT_DESC])->limit(10)->all();

    }
}

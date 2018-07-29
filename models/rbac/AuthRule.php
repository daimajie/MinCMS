<?php

namespace app\models\rbac;

use Yii;
use yii\base\Model;
use yii\rbac\Rule;

class AuthRule extends Model
{
    public $name;
    public $data;
    public $className;
    public $created_at;
    public $updated_at;

    private $_item;

    public function __construct($item, $config = [])
    {
        $this->_item = $item;
        if ($item !== null) {
            $this->name = $item->name;
            $this->className = get_class($item);
        }
        parent::__construct($config);
    }

    //规则
    public function rules()
    {
        return [
            [['name', 'className'], 'required'],
            [['className'], 'string'],
            [['className'], 'classExists']
        ];
    }

    //检测类是否存在
    public function classExists()
    {
        if (!class_exists($this->className)) {
            $this->addError('className', '请正确指定一个以定义的类。');
            return;
        }
        if (!is_subclass_of($this->className, Rule::className())) {
            $this->addError('className', '规则类必须继承自yii\rbac\Rule。');
        }
    }

    //标签
    public function attributeLabels()
    {
        return [
            'name' => '规则名称',
            'className' => '规则类全名称',
        ];
    }

    //获取当前规则
    public function getItem()
    {
        return $this->_item;
    }

    //检测是否为新规则
    public function getIsNewRecord()
    {
        return $this->_item === null;
    }

    //根据id获取规则实例
    public static function find($id)
    {
        $item = Yii::$app->authManager->getRule($id);
        if ($item !== null) {
            return new static($item);
        }
        return null;
    }

    public function save()
    {
        if ($this->validate()) {
            $manager = Yii::$app->authManager;
            $class = $this->className;

            //如果当前规则为空表示新建
            if ($this->_item === null) {
                $this->_item = new $class();
                $isNew = true;
            } else {
                $isNew = false;
                $oldName = $this->_item->name;
            }
            $this->_item->name = $this->name;

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


}

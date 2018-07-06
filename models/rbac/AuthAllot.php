<?php
namespace app\models\rbac;
use yii\base\Model;

class AuthAllot extends Model
{
    public $item;
    public $roles;
    public $permission;

    public function attributeLabels()
    {
        return [
            'item' => '权限或角色',
            'roles' => '可用角色',
            'permission' => '可用权限'
        ];
    }
}
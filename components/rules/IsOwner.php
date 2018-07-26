<?php
namespace app\components\rules;
use yii\rbac\Rule;
use app\models\Post;

class IsOwner extends Rule
{
    //名字
    public $name = 'IsOwner';


    //判断是否有修改文章的权限
    public function execute($user, $item, $params)
    {
        return isset($params['post']) ? $params['post']->createdBy == $user : false;
    }
}
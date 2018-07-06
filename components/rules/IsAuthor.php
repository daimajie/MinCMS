<?php
namespace app\components\rules;
use yii\rbac\Rule;

class IsAuthor extends Rule
{
    //名字
    public $name = 'isAuthor';


    //判断是否有修改文章的权限
    public function execute($user, $item, $params)
    {
        return isset($params['article']) ? $params['article']->user_id == $user : false;
    }
}
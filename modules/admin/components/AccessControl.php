<?php
/**
 * Created by PhpStorm.
 * User: lvcheng
 * Date: 18-7-26
 * Time: 下午10:16
 */
namespace app\modules\admin\components;

use Yii;
use yii\web\ForbiddenHttpException;

class AccessControl extends \yii\base\ActionFilter
{
    public function beforeAction ($action)
    {

        // 获取当前路由
        $actionId = $action->id;
        $controllerId = $action->controller->id;
        $moduleId = $action->controller->module->id;

        $controllerRoute = $moduleId .'/'. $controllerId;
        $actionRoute = $moduleId .'/'. $controllerId .'/'. $actionId;

        // 当前登录用户
        $user = Yii::$app->getUser();


        //如果没有后台权限 如果没有跳到网站首页
        if($actionRoute == 'admin/default/error')
            return true;

        if(
            ($user->can('admin') || $user->can('author'))
            &&
            ($actionRoute == 'admin/default/index' || $actionRoute == 'admin/default/frame')
        ){
            return true;
        }

        //判断是否控制器权限
        if ($user->can($controllerRoute . '/*')) {
            return true;
        }

        //判断动作权限
        if ($user->can($actionRoute)) {
            return true;
        }



        $this->denyAccess($user);
    }

    /**
     *  拒绝用户访问
     *  访客，跳转去登录；已登录，抛出403
     *  @param $user 当前用户
     *  @throws ForbiddenHttpException 如果用户已经登录，抛出403.
     */
    protected function denyAccess($user)
    {

        if ($user->getIsGuest()) {

            $user->loginRequired();
        } else {
            throw new ForbiddenHttpException('不允许访问.');
        }
    }
}
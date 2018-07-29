<?php
/**
 * Created by PhpStorm.
 * User: lvcheng
 * Date: 18-7-26
 * Time: 下午8:13
 */
namespace app\commands;
use yii\console\Controller;
use app\components\rules\AuthorRule;
use Yii;

class AuthorRuleController extends Controller{

    //添加规则
    public function actionAddRule(){
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $auth = Yii::$app->authManager;

            // 添加规则
            $rule = new AuthorRule();
            $auth->add($rule);

            // 添加 "updateOwnPost" 权限并与规则关联
            $owner = $auth->createPermission('owner');
            $owner->description = '更新删除自己创建的项目';
            $owner->ruleName = $rule->name;
            $auth->add($owner);

            // 文章的编辑 删除 放置回收站  从回收站恢复文章
            $viewArticle = $auth->getPermission('content/article/view');
            $auth->addChild($owner, $viewArticle);

            $updateArticle = $auth->getPermission('content/article/update');
            $auth->addChild($owner, $updateArticle);

            $deleteArticle = $auth->getPermission('content/article/delete');
            $auth->addChild($owner, $deleteArticle);

            $putRecycle = $auth->getPermission('content/article/put-recycle');
            $auth->addChild($owner, $putRecycle);

            $reStore = $auth->getPermission('content/article/re-store');
            $auth->addChild($owner, $reStore);

            //话题的编辑 删除
            $viewTopic = $auth->getPermission('content/topic/view');
            $auth->addChild($owner, $viewTopic);

            $updateTopic = $auth->getPermission('content/topic/update');
            $auth->addChild($owner, $updateTopic);

            $deleteTopic = $auth->getPermission('content/topic/delete');
            $auth->addChild($owner, $deleteTopic);


            // 允许 "author" 更新自己的帖子
            $author = $auth->getRole('作者');
            $auth->addChild($author, $owner);

            $transaction->commit();

            echo "create rule success!\n";
            return Controller::EXIT_CODE_NORMAL;
        } catch(\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch(\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }


}
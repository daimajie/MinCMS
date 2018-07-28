<?php
/**
 * Created by PhpStorm.
 * User: lvcheng
 * Date: 18-7-26
 * Time: 下午9:25
 */
namespace app\commands;
use app\models\member\User;
use yii\console\Controller;
use Yii;

class InitMemberController extends Controller
{
    //添加管理员
    public function actionAddAdmin(){
        try{
            if(!$user = $this->addMember())
                throw new \Exception('create manger fail.');

            // 要添加以下三行代码：
            $auth = Yii::$app->authManager;
            $adminRole = $auth->getRole('管理员');
            $auth->assign($adminRole, $user->getId());

            echo "create manager success!\n";
            return Controller::EXIT_CODE_NORMAL;
        }catch (\Exception $e){
            echo $e->getMessage()."\n";
            return Controller::EXIT_CODE_ERROR;
        }
    }

    //添加作者
    public function actionAddAuthor(){
        try{
            if(!$user = $this->addMember())
                throw new \Exception('create author fail。');
            // 要添加以下三行代码：
            $auth = Yii::$app->authManager;
            $authorRole = $auth->getRole('作者');
            $auth->assign($authorRole, $user->getId());
            echo "create author success!\n";
            return Controller::EXIT_CODE_NORMAL;
        } catch (\Exception $e){
            echo $e->getMessage() . "\n";
            return Controller::EXIT_CODE_ERROR;
        }
    }

    /**
     * 获取输入信息
     */
    private function addMember(){
        $username = $this->prompt('username: ', ['require' => true, 'validator' => function($input, &$error) {
            if (strlen($input) < 5) {
                $error = 'The Pin must be Greater than or equal to 5 chars!';
                return false;
            }
            return true;
        }]);
        $password = $this->prompt('password: ', ['require' => true,'validator' => function($input, &$error) {
            if (strlen($input) < 6) {
                $error = 'The Pin must be Greater than or equal to 6 chars!';
                return false;
            }
            return true;
        }]);
        $email = $this->prompt('email: ', ['require' => true, 'validator' => function($input, &$error) {
            if (!filter_var($input, FILTER_VALIDATE_EMAIL))
            {
                $error = 'The Pin must be email!';
                return false;
            }
            return true;
        }]);
        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->generatePasswordHash($password);
        $user->generateAuthKey();

        //保存数据
        if(!$user->save(false)){
            //throw new \Exception('create manger fail。');
            return false;
        }
        return $user;
    }
}
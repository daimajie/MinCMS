<?php
namespace app\controllers;

use app\components\helper\Helper;
use app\models\content\Article;
use app\models\content\Topic;
use app\models\member\ForgetForm;
use app\models\member\LoginForm;
use app\models\member\RegisterForm;
use app\models\member\ResetPasswordForm;
use app\models\setting\Friend;
use Yii;
use yii\base\Exception;
use yii\web\BadRequestHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\Response;

class IndexController extends BaseController
{
    //方法**
    public function actions() {
        return [
            //验证码
            'captcha' =>  [
                'class' => 'yii\captcha\CaptchaAction',
                'height'=>38,
                'width' => 85,
                'minLength' => 4,
                'maxLength' => 4,

            ],
        ];
    }

    //首页**
    public function actionIndex(){

        //获取文章列表
        $data = Article::getArticles();

        //获取热门文章9条
        $hot = Article::getHotArticles();

        //获取热门话题
        $hotTopic = Topic::getHotTopic();

        //获取友链
        $friends = Friend::find()->orderBy(['sort'=>SORT_ASC,'id'=>SORT_DESC])->limit(15)->asArray()->all();


        return $this->render('index',[
            'articles' => $data['articles'],
            'pagination' => $data['pagination'],
            'hot' => $hot,
            'hotTopic' => $hotTopic,
            'friends' => $friends,
        ]);
    }

    //用户登录**
    public function actionLogin(){
        if(!Yii::$app->user->isGuest)
            return $this->redirect(['index/index']);

        $model = new LoginForm();

        //表单提交
        if (Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->login()){
                $this->redirect(['index/index']);
            }
            //登录失败显示表单及错误
        }

        return $this->render('login',[
            'model' => $model,
        ]);

    }

    //用户登出**
    public function actionLogout(){
        if(Yii::$app->user->isGuest)
            return $this->redirect(['index/index']);

        Yii::$app->user->logout();
        return $this->redirect(['index/index']);

    }

    //用户注册
    public function actionRegister(){
        $model = new RegisterForm();

        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->register()){
                Yii::$app->session->setFlash('info', '注册成功，现在可以去登录了。');
                return $this->redirect(['index/login']);
            }
        }

        return $this->render('register',[
            'model' => $model
        ]);
    }

    //找回密码请求**
    public function actionForget(){
        $model = new ForgetForm();

        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->sendEmail()){
                Yii::$app->session->setFlash('info', '发送邮件成功，请查收。');
                return $this->redirect(['index/step','step'=>'sent']);
            }
        }
        return $this->render('forget',[
            'model' => $model
        ]);
    }

    //重置密码**
    public function actionResetPassword($token){
        try{
            $model = new ResetPasswordForm($token);

            if(Yii::$app->request->isPost){
                if($model->load(Yii::$app->request->post()) && $model->reset()){
                    //重置成功
                    Yii::$app->session->setFlash('info', '重置密码成功。');
                    return $this->redirect(['index/step','step'=>'success']);

                }
            }

            return $this->render('reset-password',[
                'model' => $model,
            ]);

        }catch (\Exception $e){
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->redirect(['index/login']);
        }


    }

    //步骤
    public function actionStep(){

        return $this->render('step');
    }

    //发送邮箱验证码**
    public function actionSendCaptcha(){

        try{
            //验证请求方式
            Yii::$app->response->format = Response::FORMAT_JSON;
            if(!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方式不被允许。');

            //验证邮箱格式
            $email = Yii::$app->request->post('email');
            if(empty($email) || !filter_var($email,FILTER_VALIDATE_EMAIL)){
                throw new BadRequestHttpException('请指定正确邮箱地址。');
            }

            //生成验证码
            $captcha = Helper::generateCaptcha(6, 5, 'email-captcha');

            //发送验证码
            $view = 'message/send-captcha';
            $subject = Yii::$app->name . ' - 邮箱验证码';
            $ret = Helper::sendEmail(
                Yii::$app->params['adminEmail'], //from
                $email,                          //to
                $subject,                        //subject
                $view,                           //view
                [                                //variable
                    'captcha'=> $captcha,
                    'name' => Yii::$app->name,
                ]
            );

            if($ret)
                return ['errno'=>0, 'message' => '邮件已成功发送。'];

            return ['errno'=>1, 'message' => '邮件发送失败，请稍后再试。'];

        }catch (MethodNotAllowedHttpException $e){

            return $this->redirect(['/index/index']);
        }catch (Exception $e){

            return ['errno' => 1, 'message'=>$e->getMessage()];
        }
    }
}
<?php
namespace app\controllers;

use app\models\collect\Collect;
use app\models\content\Article;
use app\models\member\Profile;
use app\models\member\ResetForm;
use app\models\member\User;
use yii\base\Exception;
use yii\filters\AccessControl;
use Yii;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class MemberController extends BaseController
{


    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['*'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'upload' => [
                'class' => 'app\components\actions\UploadAction',
                //剪切尺寸
                'shearSize' => [200, 200],
                //保存子目录
                'subDir' => 'avatar',
                //是否划分日期目录(默认true)
                //'randDir' => true,
            ]
        ];
    }

    //用户信息
    public function actionIndex(){


        return $this->render('index',[
            'user' => Yii::$app->user->identity,
        ]);
    }

    //用户信息设置
    public function actionSet(){
        $user_id = Yii::$app->user->id;

        if(!$model = Profile::findOne(['user_id'=>$user_id])) $model = new Profile();

        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->save()){
                //保存成功
                Yii::$app->session->setFlash('info', '设置个人信息成功.');
                return $this->refresh();
            }
        }

        return $this->render('set',[
            'model' => $model,
            'user' => Yii::$app->user->identity,
        ]);
    }

    //成为作者
    public function actionWriting(){
        return $this->render('writing',[
            'user' => Yii::$app->user->identity,
        ]);

    }

    //设置头像
    public function actionImage(){

        return $this->render('image',[
            'user' => Yii::$app->user->identity,
        ]);
    }

    //设置密码
    public function actionResetPasswd(){
        $model = new ResetForm();
        $model->scenario = ResetForm::RESET_PASSWD;

        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->setPasswd()){
                //设置成功
                Yii::$app->session->setFlash('info','设置成功。');
                return $this->refresh();
            }
        }

        return $this->render('reset-passwd',[
            'user' => Yii::$app->user->identity,
            'model' => $model,
        ]);
    }

    //设置邮箱
    public function actionResetEmail(){
        $model = new ResetForm();
        $model->scenario = ResetForm::RESET_EMAIL;

        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->setEmail()){
                //设置成功
                Yii::$app->session->setFlash('info','设置成功。');
                return $this->refresh();
            }
        }

        return $this->render('reset-email',[
            'user' => Yii::$app->user->identity,
            'model' => $model,
        ]);
    }

    //likes articles
    public function actionLikes(){
        $articles = Collect::getLikes(Yii::$app->user->id);


        return $this->render('likes',[
            'user' => Yii::$app->user->identity,
            'articles'=>$articles['articles'],
            'pagination' => $articles['pagination'],
            'info' => ['text'=>'取消喜欢','info'=>'喜欢时间'],
            'cancelUrl' => Url::to(['member/cancel-likes'])
        ]);
    }

    //collect articles
    public function actionCollect(){
        $articles = Collect::getCollects(Yii::$app->user->id);

        return $this->render('likes',[
            'user' => Yii::$app->user->identity,
            'articles'=>$articles['articles'],
            'pagination' => $articles['pagination'],
            'info' => ['text'=>'取消收藏','info'=>'收藏时间'],
            'cancelUrl' => Url::to(['member/cancel-collects'])
        ]);
    }

    //cancel likes
    public function actionCancelLikes(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            //判断是否ajax
            if(!Yii::$app->request->isAjax){
                throw new MethodNotAllowedHttpException('请求方式不被允许。');
            }

            $id = (int) Yii::$app->request->post('id');
            if(empty($id) || $id <= 0){
                throw new BadRequestHttpException('请求参数错误。');
            }

            $collect = Collect::findOne($id);
            if(!$collect)
                throw new NotFoundHttpException('没有相关数据。');

            Article::updateAllCounters(['likes'=>-1], ['id'=>$collect->article_id]);

            if($collect->delete() === false){
                throw new Exception('取消喜欢失败，请重试。');
            }



            return ['errno'=>0, 'message' => '取消喜欢成功。'];

        }catch (MethodNotAllowedHttpException $e){
            return $this->redirect(['index/index']);
        }catch (Exception $e){
            return ['errno'=>1, 'message' => $e->getMessage()];
        }
    }

    //cancel collect
    public function actionCancelCollects(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            //判断是否ajax
            if(!Yii::$app->request->isAjax){
                throw new MethodNotAllowedHttpException('请求方式不被允许。');
            }

            $id = (int) Yii::$app->request->post('id');
            if(empty($id) || $id <= 0){
                throw new BadRequestHttpException('请求参数错误。');
            }

            $likes = Collect::findOne($id);
            if(!$likes) throw new NotFoundHttpException('没有相关数据。');

            Article::updateAllCounters(['collect'=>-1], ['id'=>$likes->article_id]);

            if($likes->delete() === false){
                throw new Exception('取消收藏失败，请重试。');
            }

            return ['errno'=>0, 'message' => '取消收藏成功。'];

        }catch (MethodNotAllowedHttpException $e){
            return $this->redirect(['index/index']);
        }catch (Exception $e){
            return ['errno'=>1, 'message' => $e->getMessage()];
        }
    }

    public function actionSetAvatar(){
        if(Yii::$app->request->isPost){

            //获取头像信息
            $avatar = trim(Yii::$app->request->post('avatar'));
            if(!empty($avatar)){
                $model = User::findOne(Yii::$app->user->id);
                $model->image = $avatar;

                //删除原有头像
                if($model->image !== $model->getOldAttribute('image')){
                    User::deleteImg($model->getOldAttribute('image'));
                }

                if($model->save(false)){
                    //设置成功
                    Yii::$app->session->setFlash('info','设置头像成功。');

                }
            }
        }
        return $this->redirect(['member/image']);

    }





}
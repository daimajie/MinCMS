<?php
namespace app\modules\admin\modules\content\controllers;
use app\models\content\ArticleTag;
use app\models\content\SearchTag;
use app\models\content\Tag;
use app\models\content\Topic;
use app\modules\admin\controllers\BaseController;
use yii\base\Exception;
use yii\web\BadRequestHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use Yii;
use yii\web\Response;

class TagController extends BaseController
{
    //列表
    public function actionIndex(){

        $searchModel = new SearchTag();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        //下拉框初始数据
        $selectArr = ['' => '按话题搜索'];
        if(isset($searchModel->topic_id) && $searchModel->topic_id > 0){
            $selectArr[$searchModel->topic_id] = Topic::find()
                ->where(['id'=>$searchModel->topic_id])
                ->select('name')->scalar();
            $selectArr += ['0'=>'所有分类'];
        }


        return $this->render('index',[
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'selectArr' => $selectArr
        ]);
    }

    //创建
    public function actionCreate(){
        $model = new Tag();
        $selectArr = ['' => '选择所属话题'];

        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->save()){
                //新建成功
                Yii::$app->session->setFlash('success', '新建标签成功。');
                return $this->redirect(['index']);
            }
            //创建失败 显示表单及错误
            if(!empty($model->topic_id) && $model->topic_id > 0){
                $topic = Topic::find()->select(['id','name'])->where(['id'=>$model->topic_id])->one();

                $selectArr += [$topic->id => $topic->name];
            }
        }

        //标签管理
        return $this->render('create',[
            'model' => $model,
            'selectArr' => $selectArr,
        ]);

    }

    //查看
    public function actionView($id){
        $model = static::getModel($id);

        return $this->render('view',[
            'model' => $model
        ]);

    }

    //修改
    public function actionUpdate($id){
        $model = static::getModel($id);

        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->save()){
                //修改完成
                Yii::$app->session->setFlash('success', '修改标签成功。');
                //跳转
                if(Yii::$app->request->get('action') === 'ajax')
                    return $this->redirect(['topic/index','id'=>$model->topic_id]);
                else
                    return $this->redirect(['index']);
            }
            //修改失败 显示表单
        }


        //获取所属分类信息
        if($model->topic_id){
            $selectArr[$model->topic_id] = Topic::find()
                ->where(['id'=>$model->topic_id])
                ->select('name')->scalar();
        }

        if(Yii::$app->request->isAjax){
            return $this->render('create',[
                'model' => $model,
                'selectArr' => $selectArr
            ]);
        }else{
            return $this->renderAjax('create',[
                'model' => $model,
                'selectArr' => $selectArr
            ]);
        }

    }

    //删除
    public function actionDelete($id){
        $model = static::getModel($id);

        //删除标签及文章标签关联数据
        if($model->delete()) {
            //删除文章标签关联数据
            ArticleTag::deleteAll(['tag_id' => $id]);

            //删除成功
            Yii::$app->session->setFlash('success', '删除标签成功。');

        }else
            //删除失败
            Yii::$app->session->setFlash('error', '删除失败，请重试。');

        return $this->redirect(['index']);
    }

    //ajax 编辑标签
    /*public function actionAjaxEdit($id){
        //请求表单
        $model = static::getModel($id);

        if(Yii::$app->request->isPost){
            //保存数据请求
            if($model->load(Yii::$app->request->post()) && $model->save()){
                //修改完成
                Yii::$app->session->setFlash('success', '修改标签成功。');
                return $this->redirect(['index']);
            }
            //修改失败 显示表单
        }


        //获取所属分类信息
        if($model->topic_id){
            $selectArr[$model->topic_id] = Topic::find()
                ->where(['id'=>$model->topic_id])
                ->select('name')->scalar();
        }
        return $this->renderAjax('create',[
            'model' => $model,
            'selectArr' => $selectArr
        ]);
    }*/

    //ajax 删除标签
    public function actionAjaxDelete(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            if(!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方式不被允许。');

            $id = Yii::$app->request->post('tag_id');

            $model = static::getModel($id);

            if($model->delete() === false){
                throw new Exception('删除标签失败，请重试。');
            }

            //删除标签文章关联数据
            ArticleTag::deleteAll(['tag_id' => $id]);

            //删除成功
            return ['errno'=>0, 'message'=>'删除标签成功。'];

        }catch (MethodNotAllowedHttpException $e){
            return $this->redirect(['/']);
        }catch (Exception $e){
            return ['errno'=>1,'message'=>$e->getMessage()];

        }
    }

    //批量删除
    public function actionBatchDel(){
        $tags_id = Yii::$app->request->post('tags_id');

        //检测参数
        if(empty($tags_id) || !is_array($tags_id)){
            Yii::$app->session->setFlash('error', '请选择要删除的标签。');
            return $this->redirect(['index']);
        }

        //删除记录
        if(Tag::deleteAll(['in', 'id', $tags_id]) === false){

            Yii::$app->session->setFlash('error', '删除标签失败，请重试。');
        }else{
            //删除文章标签关联数据
            ArticleTag::deleteAll(['in', 'tag_id', $tags_id]);

            Yii::$app->session->setFlash('success', '批量删除标签成功。');
        }
        return $this->redirect(['index']);
    }

    //获取模型
    private static function getModel($id){
        $id = (int) $id;
        if($id <= 0)
            throw new BadRequestHttpException('请求错误。');

        $model = Tag::findOne($id);

        if (!$model)
            throw new NotFoundHttpException('没有相关数据。');

        return $model;
    }

    //根据话题id 获取其下所有标签
    public function actionGetTags(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            if(!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方式不被允许。');

            //获取 参数
            $topic_id = (int)Yii::$app->request->post('topic_id');

            //获取标签
            $data = Tag::getTagsByTopic($topic_id);

            return [
                'errno' => 0,
                'data' => $data
            ];
        }catch (MethodNotAllowedHttpException $e){
            return $this->redirect(['/']);
        }catch (Exception $e){
            return ['errno'=>1, 'message'=>$e->getMessage()];
        }
    }


}
<?php

namespace app\models\content;

use app\models\member\User;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use Yii;
use yii\data\Pagination;
use yii\helpers\FileHelper;
use yii\helpers\VarDumper;
use yii\web\BadRequestHttpException;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%article}}".
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%article}}';
    }

    //auto complete
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'user_id',
                'updatedByAttribute' => false
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'words', 'recommend', 'checked', 'draft', 'recycle', 'visited', 'comment', 'likes', 'collect', 'topic_id', 'content_id', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 125],
            [['brief'], 'string', 'max' => 512],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'title' => '文章标题',
            'brief' => '文章简介',
            'image' => '文章图片',
            'type' => '文章类型',
            'words' => '字数',
            'recommend' => '推荐阅读',
            'checked' => '设查',
            'draft' => '草稿箱',
            'recycle' => '回收站',
            'visited' => '浏览次数',
            'comment' => '评论次数',
            'likes' => '喜欢次数',
            'collect' => '收藏次数',
            'topic_id' => '所属话题',
            'content_id' => '文章内容',
            'user_id' => '作者',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }



    //钩子函数
    public function afterDelete()
    {
        parent::afterDelete();
        //分类count累减
        Topic::updateAllCounters(['count'=>-1], ['id'=>$this->topic_id]);
        User::updateAllCounters(['count'=>-1], ['id'=>$this->user_id]);
    }

    /**
     * 删除文章图片
     */
    public static function deleteImg($image){
        //获取图片上传路径
        $upPath = Yii::$app->params['imgPath']['imgUp'] . '/';

        //直接删除
        @FileHelper::unlink($upPath . $image);

        //返回
        return true;
    }

    /**
     * 关联内容
     */
    public function getContent(){
        return $this->hasOne(Content::class, ['id'=>'content_id']);
    }
    /**
     * 关联话题
     */
    public function getTopic(){
        return $this->hasOne(Topic::class, ['id'=>'topic_id'])->select(['id','name']);
    }

    /**
     * 关联标签
     */
    public function getTags(){
        return $this->hasMany(Tag::class, ['id'=>'tag_id'])
            ->viaTable('{{%article_tag}}',['article_id'=>'id'])
            ->select(['id','name']);
    }

    /**
     * 关联作者
     */
    public function getUser(){
        return $this->hasOne(User::class, ['id'=>'user_id'])->select(['id','username','image','lasttime','count']);
    }

    /**
     * 删除文章
     */
    public function delteArticle(){
        $transation = Yii::$app->db->beginTransaction();

        try{
            //删除内容
            if(Content::deleteAll(['id'=>$this->content_id]) === false){
                throw new Exception('删除文章内容失败，请重试。');
            }

            //删除标签关联
            if(ArticleTag::deleteAll(['article_id'=>$this->id]) === false){
                throw new Exception('删除标签关联数据失败，请重试。');
            }

            //删除图片
            if(!empty($this->image)){
                static::deleteImg($this->image);
            }

            //删除文章
            if($this->delete() ===false){
                throw new Exception('删除文章失败，请重试。');
            }

            $transation->commit();
            return true;
        }catch (\Exception $e){
            $transation->rollBack();
            throw $e;
        }catch (\Throwable $e){
            $transation->rollBack();
            throw $e;

        }

    }

    private static function getQuery(){
        $query = static::find()
            ->andFilterWhere(['!=','checked', 1]) //通过审核的
            ->andFilterWhere(['!=','draft', 1]) //不再草稿箱（发布的文章）
            ->andFilterWhere(['!=','recycle', 1]) //不再回收站的文章
            ->orderBy(['created_at'=>SORT_DESC])
            ->with(['user', 'topic']);
        return $query;
    }
    private static function getSingleQuery(){
        $query = static::find()
            ->andFilterWhere(['!=','checked', 1]) //通过审核的
            ->andFilterWhere(['!=','draft', 1]) //不再草稿箱（发布的文章）
            ->andFilterWhere(['!=','recycle', 1]) //不再回收站的文章
            ->orderBy(['created_at'=>SORT_DESC]);
            //->with(['user', 'topic']);
        return $query;
    }

    /**
     * 获取文章列表数据根据话题
     * @param $topic_id int #话题id
     * @throws BadRequestHttpException
     * @return array #文章数据
     */
    public static function getArticlesByTopic($topic_id){
        $topic_id = (int) $topic_id;
        if($topic_id <= 0)
            throw new BadRequestHttpException('请求参数错误。');

        //检测是否筛选标签
        $where = [];
        $tag_id = Yii::$app->request->get('tag', '');

        if(is_numeric($tag_id) && $tag_id>0){
            $where = ['at.tag_id'=>$tag_id];
        }elseif($tag_id === 'unset'){
            $where = "isnull(at.tag_id)";
        }

        $query = self::getQuery()
            ->alias('a')
            ->leftJoin(['at'=>"{{%article_tag}}"],'at.article_id = a.id')
            ->select(['a.*','at.*'])
            ->andWhere($where)
            ->andFilterWhere(['a.topic_id'=>$topic_id]);
            //->createCommand()->getSql();
            //->asArray()->all();
        //VarDumper::dump($query, 10, 1);die;
        $count = $query->count();

        $pagination = new Pagination(['totalCount' => $count,'pageSize' => 15]);

        $articles = $query
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();
        return [
            'articles' => $articles,
            'pagination' => $pagination
        ];
    }


    /**
     * 获取文章列表 首页列表数据
     */
    public static function getArticles(){
        $query = static::getQuery();
        $count = $query->count();

        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => 15]);

        $articles = $query->offset($pagination->offset)->limit($pagination->limit)->asArray()->all();

        return [
            'articles' => $articles,
            'pagination' => $pagination
        ];

    }

    /**
     * 获取热门文章9条
     */
    public static function getHotArticles(){
        $ret = self::find()
            ->andFilterWhere(['!=','checked', 1]) //通过审核的
            ->andFilterWhere(['!=','draft', 1]) //不再草稿箱（发布的文章）
            ->andFilterWhere(['!=','recycle', 1]) //不再回收站的文章
            ->orderBy(['comment'=>SORT_DESC, 'visited'=>SORT_DESC])
            ->select(['id','title'])
            ->asArray()
            ->all();
        return $ret;
    }

    /**
     * 获取文章详情
     */
    public static function getDetail($id){
        $ret = self::find()
            ->with(['user','topic','tags'/*,'content'*/])
            ->where(['id' => $id])
            ->asArray()
            ->one();

        return $ret;

    }

    /**
     * 获取上一篇和下一篇
     * @param int $article_id #当前文章id
     * @param int $subject_id #当前专题
     * @return array
     */
    public static function prevAndNext($article_id, $topic_id){
        $query = static::find()
            ->select(['id','title'])
            ->andFilterWhere(['!=','checked', 1]) //通过审核的
            ->andFilterWhere(['!=','draft', 1]) //不再草稿箱（发布的文章）
            ->andFilterWhere(['!=','recycle', 1]) //不再回收站的文章
            ->andFilterWhere(['topic_id'=>$topic_id]);//指定话题


        $previous = $query
            ->andFilterWhere(['<', 'id', $article_id])
            ->orderBy(['id'=>SORT_DESC])
            ->limit(1)
            ->one();

        $next = self::find()
            ->andFilterWhere(['>', 'id', $article_id])
            ->orderBy(['id'=>SORT_ASC])
            ->limit(1)
            ->one();
        //拼接url
        $prev_article = [
            'url' => !is_null($previous) ? Url::current(['id'=>$previous->id]):'javascript:void(0);',
            'title' => !is_null($previous)?$previous->title:'已经是第一篇了',
        ];
        $next_article = [
            'url' => !is_null($next)?Url::current(['id'=>$next->id]):'javascript:void(0);',
            'title' => !is_null($next)?$next->title:'已经是最后一篇了',
        ];
        return [
            'prev' => $prev_article,
            'next' => $next_article
        ];
    }




}

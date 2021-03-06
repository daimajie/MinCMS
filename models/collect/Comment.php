<?php

namespace app\models\collect;

use app\models\content\Article;
use app\models\member\User;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\data\Pagination;
use yii\helpers\VarDumper;
use yii\widgets\LinkPager;

/**
 * This is the model class for table "{{%comment}}".
 *
 * @property int $id 主键
 * @property string $content 内容
 * @property int $user_id 用户
 * @property int $article_id 文章
 * @property int $type 类型
 * @property int $comment_id 评论
 * @property int $created_at 创建时间
 */
class Comment extends \yii\db\ActiveRecord
{
    const REPLY = 'reply';
    const COMMENT = 'comment';
    const UPDATE = 'update';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%comment}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
            ],
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'user_id',
                'updatedByAttribute' => false
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content'], 'required', 'on' => self::UPDATE],
            [['content','article_id','type','comment_id'], 'required','on'=>[self::REPLY,self::COMMENT]],
            [['content'], 'trim','on'=>[self::REPLY,self::COMMENT]],
            [['content'], 'string','on'=>[self::REPLY,self::COMMENT]],
            [['type'], 'in', 'range' => [0,1],'on'=>[self::REPLY,self::COMMENT]],
            [['article_id', 'comment_id'], 'integer','on'=>[self::REPLY,self::COMMENT]],
            [['article_id'], 'exist', 'targetClass' => Article::class, 'targetAttribute' => 'id','on'=>[self::REPLY,self::COMMENT]],
            [['comment_id'], 'exist', 'targetClass' => self::class, 'targetAttribute' => 'id','on'=>[self::REPLY]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'content' => '内容',
            'user_id' => '用户',
            'article_id' => '文章',
            'type' => '类型',
            'comment_id' => '评论',
            'created_at' => '创建时间',
        ];
    }

    public function getUser(){
        return $this->hasOne(User::class, ['id'=>'user_id'])->select(['id','username','image']);
    }


    /**
     * 关联回复
     */
    public function getReplys()
    {
        return $this->hasMany(self::class, ['comment_id' => 'id'])
            ->orderBy(['created_at'=>SORT_ASC,'id'=>SORT_DESC]);
    }
    public function getArticle()
    {
        return $this->hasOne(Article::class, ['id' => 'article_id'])
            ->select(['title']);
    }
    public function getComment()
    {
        return $this->hasOne(self::class, ['id' => 'comment_id']);
    }

    /**
     * 获取指定文章的所有评论及回复
     * @param $article_id int #文章id
     * @param $encodePage bool #是否生成分页heml
     * @return array
     */
    public static function getComments($article_id, $encodePage = false){
        $query = static::find()
            ->where([
                'article_id'=>$article_id,
                'type' => 0, //0为评论
            ]);
        $count = $query->count();

        $pagination = new Pagination(['totalCount' => $count,'pageSize' => 15]);
        $pagination->route = 'comment/comments';

        $comments = $query->with(['user','replys','replys.user'])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy(['created_at'=>SORT_DESC,'id'=>SORT_DESC])
            ->asArray()
            ->all();

        $pager = '';
        if($encodePage){
            $pager = LinkPager::widget([
                'pagination' => $pagination,
                'options' => ['tag'=>'div','class'=>'ui pagination menu tiny','id'=>'pager'],
                'linkContainerOptions' => ['tag'=>'dev'],
                'linkOptions' => ['class' => 'item'],
                'nextPageLabel' => '下一页',
                'prevPageLabel' => '上一页',
                'disabledPageCssClass' => 'item',
                'disableCurrentPageButton' => true
            ]);
        }


        return [
            'comments' => $comments,
            'pagination' => $encodePage ? $pager : $pagination
        ];
    }

    /**
     * 格式化评论数据（头像 时间 是否是本人评论）
     */
    public static function formatData($data){
        if(empty($data))
            return [];

        foreach ($data as $key => &$comment){
            $comment['user']['image'] = $comment['user']['image'] ? IMG_ROOT . $comment['user']['image'] : Yii::$app->params['image'];
            $comment['created_at'] = Yii::$app->formatter->asRelativeTime($comment['created_at']);
            $comment['isself'] = Yii::$app->user->isGuest ? false : $comment['user_id'] == Yii::$app->user->id;
            if($comment['replys'])
                $comment['replys'] = static::formatSubData($comment['replys']);
        }

        return $data;
    }
    public static function formatSubData($data){
        if(empty($data))
            return [];

        foreach ($data as $key => &$reply){
            $reply['user']['image'] = $reply['user']['image'] ? IMG_ROOT . $reply['user']['image'] : Yii::$app->params['image'];
            $reply['created_at'] = Yii::$app->formatter->asRelativeTime($reply['created_at']);
            $reply['isself'] = Yii::$app->user->isGuest ? false : $reply['user_id'] == Yii::$app->user->id;
        }

        return $data;
    }



    /**
     * #判断是否是频繁提交评论或回复(间隔时间2分钟)
     * @param $article_id int 文章id
     * @param $user_id int 用户id
     * @return bool 是否是频繁提交 是返回true 不是返回false
     */
    public static function interval($article_id, $user_id){
        return (bool) static::find()
            ->where(['and', ['article_id'=>$article_id], ['user_id'=>$user_id]])
            ->andWhere(['>=','created_at', time()-120])
            ->count();
    }


}

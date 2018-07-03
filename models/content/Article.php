<?php

namespace app\models\content;

use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use Yii;
use yii\helpers\FileHelper;

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
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        //分类count累加
        if($this->isNewRecord)
            Topic::updateAllCounters(['count'=>1], ['id'=>$this->topic_id]);
    }
    public function afterDelete()
    {
        parent::afterDelete();
        //分类count累减
        Topic::updateAllCounters(['count'=>-1], ['id'=>$this->topic_id]);
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




}

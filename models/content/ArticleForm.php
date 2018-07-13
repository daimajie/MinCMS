<?php
namespace app\models\content;
use Yii;
use yii\base\Exception;

class ArticleForm extends Article
{
    //内容
    public $content = '';
    //选择的标签
    public $tags = [];
    //新建标签
    public $newTags = '';



    public function rules()
    {
        return [
            [['title','brief','topic_id','type','content'], 'required'],
            [['image'], 'string', 'max'=>125],
            [['title'], 'string', 'max'=>125, 'min'=>3],
            [['brief'], 'string', 'max'=>512, 'min'=>5],
            [['type'], 'in', 'range' => [0,1,2], 'message' => '请正确选择文章类型'],
            [['recommend'], 'default', 'value' => 0],
            [['recommend'], 'in', 'range' => [0, 1], 'message' => '请正确选择是否推荐文章'],
            [['draft'], 'in', 'range' => [0, 1]],
            [['topic_id'], 'exist', 'targetClass' => 'app\models\content\Topic', 'targetAttribute' => 'id'],

            [['content','newTags'], 'string'],
            [['tags'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        $label = parent::attributeLabels();
        return $label + [
                'content' => '文章内容',
                'tags' => '选择标签',
                'newTags' => '新建标签'
                ];
    }

    /**
     * 存储文章
     */
    public function store(){
        //验证数据
        if(!$this->validate())
            return false;

        //丰富字数属性
        $this->setValue();

        //事务
        $transaction = Yii::$app->db->beginTransaction();
        try{

            //写入新标签
            $tagsArr = $this->wirteTags();

            //与选取标签合并
            $tagsArr = $this->mergeTags($tagsArr);

            //写入内容
            $this->writeContent();

            //写入文章
            $this->writeArticle();

            //写入文章标签关联数据
            $this->writeArtTagRelate($tagsArr);

            //话题文章数目累加
            Topic::updateAllCounters(['count'=>1], ['id'=>$this->topic_id]);

            $transaction->commit();
            return true;
        } catch(\Exception $e) {
            $transaction->rollBack();
        } catch(\Throwable $e) {
            $transaction->rollBack();
        }
        return false;
    }

    /**
     * 更新文章
     */
    public function renew(){
        //验证数据
        if(!$this->validate())
            return false;

        //丰富字数属性
        $this->setValue();

        $transaction = Yii::$app->db->beginTransaction();
        try{

            //写入新标签
            $tagsArr = $this->wirteTags();

            //与选取标签合并
            $tagsArr = $this->mergeTags($tagsArr);

            //更新内容
            $this->UpdateContent();

            //更新图片
            $this->updateImage();

            //写入文章
            $this->writeArticle();

            //删除关联数据
            $this->delArtTagRelate($this->id);

            //保存关联数据
            $this->writeArtTagRelate($tagsArr);


            $transaction->commit();
            return true;
        } catch(\Exception $e) {
            $transaction->rollBack();
        } catch(\Throwable $e) {
            $transaction->rollBack();
        }
        return false;
    }

    //赋值数据
    public function setValue(){
        $this->words = mb_strlen($this->content);
    }

    //写入标签
    private function wirteTags(){
        $tagsArr = [];
        if(!empty($this->newTags) && $this->topic_id > 0){
            $newTags = explode(',', str_replace('，', ',', $this->newTags));

            foreach ($newTags as $tag){
                $tagModel = new Tag();
                $tagModel->name = $tag;
                $tagModel->topic_id = $this->topic_id;
                if(!$tagModel->save()){
                    $this->addError('newTags', $tagModel->getFirstError('name'));
                    throw new Exception($tagModel->getFirstError('name'));
                }
                $tagsArr[] = $tagModel->id;
            }
        }
        return $tagsArr;
    }

    //合并标签id
    private function mergeTags($tagsArr){
        if(!empty($this->tags) && is_array($this->tags))
            $tagsArr = array_unique(array_merge($this->tags, $tagsArr));
        return $tagsArr;
    }

    //写入内容
    private function writeContent(){
        if(!empty($this->content)){
            $contentModel = new Content();
            $contentModel->content = $this->content;
            if(!$contentModel->save()){
                $this->addError('content', '文章内容保存失败，请重试。');
                throw new Exception('文章内容保存失败，请重试。');
            }
            //设置文章content_id 属性
            $this->content_id = $contentModel->id;
        }
    }

    //更新内容
    private function updateContent(){
        $contentModel = Content::findOne($this->content_id);
        $contentModel->content = $this->content;
        if($contentModel->save() === false){
            $this->addError('content', '更新文章内容失败');
            throw new Exception('更新文章内容失败，请重试。');
        }
    }

    //写入文章
    private function writeArticle(){
        if(!$this->save(false)){
            throw new Exception('文章保存失败，请重试。');
        }
    }

    //删除文章标签关联数据
    private function delArtTagRelate($article_id){
        if(ArticleTag::deleteAll(['article_id' => $article_id]) === false){
            $this->addError('tags', '删除文章标签关联数据失败');
            throw new Exception('删除文章标签关联数据失败，请重试。');
        }
    }

    //写入文章标签关联数据
    private function writeArtTagRelate($tagsArr){
        if(!empty($tagsArr) && is_array($tagsArr)){
            foreach ($tagsArr as $val){
                $atModel = new ArticleTag();
                $atModel->article_id = $this->id;
                $atModel->tag_id = $val;

                if(!$atModel->save()){
                    $this->addError('tags', '文章标签关联数据保存失败，请重试。');
                    throw new Exception('文章标签关联数据保存失败，请重试。');
                }
            }
        }
    }

    //删除旧有图片
    private function updateImage(){
        //如果有更换图片
        $oldImage = $this->getOldAttribute('image');
        if($this->image !== $oldImage){
            static::deleteImg($oldImage);
        }
    }



}
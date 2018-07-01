<?php
namespace app\models\content;
use Yii;

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
            [['title','brief','topic_id','type','recommend','content'], 'required'],
            [['title'], 'string', 'max'=>125, 'min'=>3],
            [['brief'], 'string', 'max'=>512, 'min'=>5],
            [['type'], 'in', 'range' => [0,1,2], 'message' => '请正确选择文章类型'],
            [['recommend'], 'in', 'range' => [0, 1], 'message' => '请正确选择是否推荐文章'],
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

    public function store(){
        //验证数据
        if(!$this->validate())
            return false;

        //丰富字数属性
        $this->words = mb_strlen($this->content);

        //事务
        $tran = Yii::$app->db->beginTransaction();
        try{

            //


        }catch (\Exception $e){

        }catch (\Throwable $t){

        }
    }

}
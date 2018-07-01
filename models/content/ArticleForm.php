<?php
namespace app\models\content;

class ArticleForm extends Article
{
    //内容
    public $content = '';

    //选择的标签
    public $tags = [];

    //新建标签
    public $newTags = '';


    public function attributeLabels()
    {
        $label = parent::attributeLabels();
        return $label + [
                'content' => '文章内容',
                'tags' => '选择标签',
                'newTags' => '新建标签'
                ];
    }

}
<?php
use yii\helpers\Html;
use yii\widgets\DetailView;


?>
    <div class="ui fluid container">
        <div class="ui grid">
            <div class="sixteen wide column">
                <div class="panel">
                    <div class="panel-content" style="padding: 10px 15px;">
                        <div class="ui secondary">
                            <div class="ui compact menu">
                                <?= Html::a('<i class="edit  icon"></i>编辑', ['update','id'=>$model->id], ['class' => 'item']) ?>
                                <?= Html::a('<i class="trash icon"></i>删除', ['put-recycle','id'=>$model->id], ['class' => 'item','id'=>'del_btn']) ?>
                                <?= Html::a('<i class="reply icon"></i>返回', ['index'], ['class' => 'item']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ui grid">
            <div class="sixteen wide column">
                <div class="panel" style="padding-top: 20px;">
                    <div class="panel-content">
                        <?php
                        echo DetailView::widget([
                            'options' => [
                                'class' => 'ui very basic collapsing celled table',
                            ],
                            'template' => '<tr><th width="120">{label}</th><td{contentOptions}>{value}</td></tr>',
                            'model' => $model,
                            'attributes' => [
                                'id',
                                'title',
                                'words',
                                'visited',
                                'comment',
                                'likes',
                                'collect',
                                'user_id',
                                [
                                    'attribute' => 'image',
                                    'format' => 'raw',
                                    'class' => 'ui small image',
                                    'value' => function($model){
                                        //return Yii::$app->params['imgPath']['imgUrl'] . '/' . $model->image;
                                        return Html::img(Yii::$app->params['imgPath']['imgUrl'] . '/' . $model->image,['class'=>'ui small image']);
                                    },

                                ],
                                [
                                    'attribute'=>'type',
                                    'value' => function($model){
                                        $arr = ['原创','翻译','转载'];
                                        return $arr[$model->type];
                                    }
                                ],
                                [
                                    'attribute'=>'recommend',
                                    'value' => function($model){
                                        return $model->recommend ? '推荐文章' : '普通文章';
                                    }
                                ],
                                [
                                    'attribute'=>'checked',
                                    'value' => function($model){
                                        return $model->checked ? '审核通过，已发布。' : '等待审核';
                                    }
                                ],
                                [
                                    'attribute'=>'draft',
                                    'value' => function($model){
                                        return $model->draft ? '草稿箱文章' : '非';
                                    }
                                ],
                                [
                                    'attribute'=>'recycle',
                                    'value' => function($model){
                                        return $model->recycle ? '回收站文章' : '非';
                                    }
                                ],
                                [
                                    'attribute' => 'topic_id',
                                    'label' => '所属话题',
                                    'value' => function($model){
                                        return $model->topic->name;
                                    }
                                ],
                                [
                                    'label' => '使用标签',
                                    'value' => function($model){
                                        $tagStr = '';
                                        foreach ($model->tags as $tag){
                                            $tagStr .= $tag->name . ', ';
                                        }
                                        return rtrim($tagStr,', ');
                                    }
                                ],
                                [
                                    'label' => '内容',
                                    'value' => function($model){
                                        return $model->content->content;
                                    }
                                ],
                                'created_at:datetime',
                                'updated_at:datetime',
                            ],

                        ]);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
$this->registerCss("body {padding:20px;} .help-block{color:#DB2828!important}");
$strJs = <<<JS
require(['mods/modal'],function(modal){
    $('#del_btn').click(function(){
        var that = $(this);
        modal.confirm("您确定要删除该话题吗？",{
            inPage:false
        },function(ele,obj){
            window.location = that.attr('href');
            return true;
        });
        return false;
    });
});

JS;
$this->registerJs($strJs);
?>
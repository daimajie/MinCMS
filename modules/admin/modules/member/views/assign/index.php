<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;


$action = $this->context->action->id;
?>
    <div class="ui fluid container">
        <div class="ui grid">
            <div class="sixteen wide column">
                <div class="panel">
                    <div class="panel-content" style="padding: 10px 15px;">
                        <div class="ui secondary">
                            <div class="ui compact menu">
                                <a class="item" href="<?= Url::to(['create'])?>">
                                    <i class="plus icon"></i>
                                    指派角色
                                </a>
                                <a class="item" href="javascript:window.history.back();">
                                    <i class="reply icon"></i>
                                    返回
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ui grid">
            <div class="sixteen wide column">
                <div class="panel" style="padding-top:20px;">
                    <div class="panel-content">
                        <?= GridView::widget([
                            'options' => [
                                'tag' => false,
                            ],
                            'tableOptions' => [
                                'class' => 'ui grey table celled',
                            ],

                            'dataProvider' => $dataProvider,
                            'layout' => "{items}\n{summary}\n{pager}",
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                [
                                    'attribute' => 'user_id',
                                    'label' => '用户'
                                ],
                                [
                                    'attribute' => 'roles',
                                    'label' => '拥有的角色'
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'options' => ['width'=>150],
                                    'header' => '<a href="javascript:;">操作</a>',
                                    'template' => '<div class="ui mini buttons">{assign} {delete}</div>',
                                    'buttons'=>[
                                        'assign' => function ($url, $model, $key) {
                                            return Html::a('编辑', $url, ['class'=>'ui red basic button']);
                                        },
                                        'delete' => function ($url, $model, $key) {
                                            return Html::a('删除', $url, ['class'=>'ui green basic button del-btn']);
                                        },

                                    ],
                                    'urlCreator' =>function($action, $model){
                                        return Url::to([$action, 'id' => $model['user_id']]);
                                    }
                                ],

                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>

    </div>

<?php
$this->registerCss("body {padding:20px;}");
$jsStr = <<<JS
    require(['mods/modal','jSmart'],function(modal){
        //删除询问框
        $('.del-btn').click(function(){
            var that = $(this);
            modal.confirm("确定要删除此指派吗？",{
                inPage:false
            },function(ele,obj){
                window.location = that.attr('href');
                return true;
            });
            return false;
        });
        
        
    });
JS;
$this->registerJs($jsStr);
?>
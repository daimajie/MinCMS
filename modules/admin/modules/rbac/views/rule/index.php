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
                                    新建规则
                                </a>
                                <a class="item" href="javascript:window.history.back();">
                                    <i class="reply icon"></i>
                                    返回
                                </a>
                            </div>

                            <?php
                                echo $this->render('_search', [
                                'model' => $searchModel
                                ]);
                            ?>
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
                                'name',
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'options' => ['width'=>200],
                                    'header' => '<a href="javascript:;">操作</a>',
                                    'template' => '<div class="ui mini buttons">{view} {update} {delete}</div>',
                                    'buttons'=>[
                                        'view' => function ($url, $model, $key) {
                                            return Html::a('查看', $url, ['class'=>'ui red basic button']);
                                        },
                                        'update' => function ($url, $model, $key) {
                                            return Html::a('编辑', $url, ['class'=>'ui blue basic button']);
                                        },
                                        'delete' => function ($url, $model, $key) {
                                            return Html::a('删除', $url, ['class'=>'ui green basic button quit-btn']);
                                        },

                                    ],
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
require(['mods/tab','mods/progress','mods/modal'],function(tab,progress,modal){
        tab.init('_tabs');
        progress.init('cls:_progress');
        
        $('.quit-btn').click(function(){
            var that = $(this);
            modal.confirm("确定要将此规则删除吗？",{
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
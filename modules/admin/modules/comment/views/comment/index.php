<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

?>
    <div class="ui fluid container">
        <div class="ui grid">
            <div class="sixteen wide column">
                <div class="panel">
                    <div class="panel-content" style="padding: 10px 15px;">
                        <div class="ui secondary">
                            <div class="ui compact menu">
                                <a class="item" href="javascript:window.history.back();">
                                    <i class="reply icon"></i>
                                    返回
                                </a>
                            </div>
                            <?php //$this->render('_search', ['model' => $searchModel, 'selectArr' => $selectArr]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ui grid">
            <div class="sixteen wide column">
                <?php
                $form = ActiveForm::begin([
                    'id' =>'batch-form',
                    'action' => Url::to(['batch-del']),
                    'enableClientScript' => false,
                    'enableClientValidation' => false,
                    'method' => 'post',
                ]);
                ?>
                <div class="panel" style="padding-top:20px;">
                    <div class="panel-content">
                        <?= GridView::widget([
                            'options' => [
                                'tag' => false,
                            ],
                            'tableOptions' => [
                                'class' => 'ui grey table celled',
                            ],
                            'pager' => [
                                'options'=>['class'=>'ui pagination menu tiny','style'=>'list-style:none'],
                                'linkOptions' => ['tag'=>'a', 'class' => 'item'],
                            ],

                            'dataProvider' => $dataProvider,
                            'layout' => "{items}\n{summary}\n{pager}",
                            'columns' => [
                                [
                                    'class' => 'yii\grid\SerialColumn',
                                ],
                                'id',
                                [
                                        'attribute' => 'user_id',
                                    'value'=>function($model){
                                        return $model->user->username;
                                    }
                                ],
                                'content',
                                [
                                        'attribute'=>'type',
                                    'value'=>function($model){
                                        return $model->type == 1 ? '评论' : '回复';
                                    }
                                ],
                                'created_at:datetime',

                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'options' => ['width'=>200],
                                    'header' => '<a href="javascript:;">操作</a>',
                                    'template' => '<div class="ui mini buttons">{update} {delete}</div>',
                                    'buttons'=>[
                                        'update' => function ($url, $model, $key) {
                                            return Html::a('编辑', $url, ['class'=>'ui blue basic button']);
                                        },
                                        'delete' => function ($url, $model, $key) {
                                            return Html::a('删除', $url, ['class'=>'ui green basic button del-btn']);
                                        },
                                    ],
                                ],
                            ],
                        ]); ?>
                    </div>
                </div>
                <?php
                ActiveForm::end();
                ?>
            </div>
        </div>

    </div>

<?php
$this->registerCss("body {padding:20px;}.summary{float:left}.pagination{float:right}.panel-content{overflow: hidden;}");
$jsStr = <<<JS
require(['mods/tab','mods/progress','mods/modal'],function(tab,progress,modal){
        tab.init('_tabs');
        progress.init('cls:_progress');
        
        //删除询问框
        $('.del-btn').click(function(){
            var that = $(this);
            modal.confirm("您确定要删除该标签吗？",{
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
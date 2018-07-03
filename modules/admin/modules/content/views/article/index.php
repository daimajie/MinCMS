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
                                    新建文章
                                </a>
                                <a class="item" href="javascript:window.history.back();">
                                    <i class="reply icon"></i>
                                    返回
                                </a>
                            </div>
                            &nbsp;
                            <div class="ui compact menu">
                                <a class="item <?= $action=='draft'?'active':'';?>" href="<?= Url::to(['draft'])?>">
                                    <i class="file icon"></i>
                                    草稿箱
                                </a>
                                <a class="item <?= $action=='recycle'?'active':'';?>" href="<?= Url::to(['recycle'])?>">
                                    <i class="recycle icon"></i>
                                    回收站
                                </a>
                                <a class="item <?= $action!='draft' && $action != 'recycle'?'active':'';?>" href="<?= Url::to(['index'])?>">
                                    <i class="list icon"></i>
                                    列表页
                                </a>
                            </div>

                            <?= $this->render('_search', [
                                'model' => $searchModel,
                                'selectArr'=>$selectArr
                            ])?>
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

                            'dataProvider' => $dataProvider,
                            'layout' => "{items}\n{summary}\n{pager}",
                            'columns' => [
                                'id',
                                [
                                    'attribute' => 'title',
                                    'options' => ['width'=>650],
                                ],
                                'user_id',
                                [
                                    'attribute'=>'checked',
                                    'value' => function($model){
                                        return $model->checked ? '审核通过，已发布。' : '等待审核';
                                    }
                                ],
                                [
                                    'attribute' => 'topic_id',
                                    'label' => '所属话题',
                                    'value' => function($model){
                                        return $model->topic->name;
                                    }
                                ],
                                'created_at:date',
                                //'updated_at:date',
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'options' => ['width'=>200],
                                    'header' => '<a href="javascript:;">操作</a>',
                                    'template' => '<div class="ui mini buttons">{view} {update} {put-recycle} {restore} {delete}</div>',
                                    'buttons'=>[
                                        'view' => function ($url, $model, $key) {
                                            return Html::a('查看', $url, ['class'=>'ui red basic button']);
                                        },
                                        'update' => function ($url, $model, $key) {
                                            return Html::a('编辑', $url, ['class'=>'ui blue basic button']);
                                        },
                                        'put-recycle' => function ($url, $model, $key) {
                                            return Html::a('删除', $url, ['class'=>'ui green basic button del-btn']);
                                        },
                                        'restore' => function ($url, $model, $key) {
                                            return Html::a('恢复', $url, ['class'=>'ui blue basic button']);
                                        },
                                        'delete' => function ($url, $model, $key) {
                                            return Html::a('丢弃', $url, ['class'=>'ui orange basic button quit-btn']);
                                        },

                                    ],
                                    'visibleButtons' => [
                                        'update' => $action === 'recycle' ? false : true,
                                        'put-recycle' => $action === 'recycle' ? false : true,
                                        'restore' => $action !== 'recycle' ? false : true,
                                        'delete' => $action !== 'recycle' ? false : true,
                                    ]

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
$this->registerCss("body {padding:20px;}");
$jsStr = <<<JS
require(['mods/tab','mods/progress','mods/modal'],function(tab,progress,modal){
        tab.init('_tabs');
        progress.init('cls:_progress');
        
        //删除询问框
        $('.del-btn').click(function(){
            var that = $(this);
            modal.confirm("确定要将此文章放入回收站吗？",{
                inPage:false
            },function(ele,obj){
                window.location = that.attr('href');
                return true;
            });
            return false;
        });
        $('.quit-btn').click(function(){
            var that = $(this);
            modal.confirm("确定要将此文章彻底删除吗？",{
                inPage:false
            },function(ele,obj){
                window.location = that.attr('href');
                return true;
            });
            return false;
        });
        
        
        //选择所有
        $('#select-all').find('input').on('click', function(){
            $(':checkbox').prop('checked',$(this).prop('checked'));
        });
        
});
JS;
$this->registerJs($jsStr);
$recycleJs = <<<JS
        //批量删除
        $('#batch-del').on('click',function(){
            $('button.close').click();
            
            modal.confirm("您确定要删除这些分类吗？",{
                inPage:false
            },function(ele,obj){
                $('#batch-form').submit();
                return true;
            });
            return false;
        });
JS;

?>
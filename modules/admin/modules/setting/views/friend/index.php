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
                                <a class="item" href="<?= Url::to(['create'])?>">
                                    <i class="plus icon"></i>
                                    新建友链
                                </a>
                                <a class="item" id="batch-del">
                                    <i class="trash icon"></i>
                                    批量删除
                                </a>
                                <a class="item" href="javascript:window.history.back();">
                                    <i class="reply icon"></i>
                                    返回
                                </a>
                            </div>
                            <?php echo $this->render('_search', ['model' => $searchModel]); ?>
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
                                    'class' => 'yii\grid\CheckboxColumn',
                                    'headerOptions' => ['id'=>'select-all'],
                                    'name' => 'friend_id[]'
                                ],
                                'id',
                                'site',
                                'url',
                                'sort',
                                [
                                    'attribute' => '访问',
                                    'value' => function($model){
                                        return Html::a('去看看',$model->url, ['class'=>'ui small button','target'=>'_blank']);
                                    },
                                    'format' => 'raw'
                                ],
                                'created_at:date',
                                'updated_at:date',
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
            modal.confirm("您确定要删除该友链吗？",{
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
        
        //批量删除
        $('#batch-del').on('click',function(){
            $('button.close').click();
            
            modal.confirm("您确定要删除这些友链吗？",{
                inPage:false
            },function(ele,obj){
                $('#batch-form').submit();
                return true;
            });
            return false;
        });

});
JS;
$this->registerJs($jsStr);
?>
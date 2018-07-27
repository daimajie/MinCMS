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
                                    新建话题
                                </a>
                                <?php if($user->group == 2):?>
                                <a class="item" id="batch-del">
                                    <i class="trash icon"></i>
                                    批量删除
                                </a>
                                <?php endif;?>
                                <a class="item" href="javascript:window.history.back();">
                                    <i class="reply icon"></i>
                                    返回
                                </a>
                            </div>
                            <?php echo $this->render('_search', ['model' => $searchModel,'selectArr'=>$selectArr]); ?>
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
                                [
                                    'class' => 'yii\grid\CheckboxColumn',
                                    'headerOptions' => ['id'=>'select-all'],
                                    'name' => 'topics_id[]',
                                    'visible' => $user->group == 2,
                                ],
                                'id',
                                'name',
                                [
                                    'attribute' => 'finished',
                                    'value' => function($model){
                                        $arr = ['未完结','已完结'];
                                        return $arr[$model->finished];
                                    }
                                ],
                                [
                                    'attribute' => 'category_id',
                                    'label' => '所属分类',
                                    'value' => function($model){
                                        return $model->category->name;
                                    }
                                ],
                                'count',
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
$this->registerCss("body {padding:20px;}");
$jsStr = <<<JS
require(['mods/tab','mods/progress','mods/modal'],function(tab,progress,modal){
        tab.init('_tabs');
        progress.init('cls:_progress');
        
        
        //删除询问框
        $('.del-btn').click(function(){
            var that = $(this);
            modal.confirm("您确定要删除该话题吗？",{
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
            
            modal.confirm("您确定要删除这些话题吗？",{
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
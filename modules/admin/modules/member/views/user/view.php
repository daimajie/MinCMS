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
                                <?= Html::a('<i class="trash icon"></i>删除', ['delete','id'=>$model->id], ['class' => 'item','id'=>'del_btn']) ?>
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

                            'model' => $model,
                            'attributes' => [
                                'id',
                                'username',
                                'email',
                                [
                                    'attribute' => 'image',
                                    'format' => 'raw',
                                    'class' => 'ui small image',
                                    'value' => function($model){
                                        if(!empty($model->image)){
                                            return Yii::$app->params['imgPath']['imgUrl']. '/' . $model->image;
                                        }else
                                            return Html::img(Yii::$app->params['image']);
                                    }
                                ],
                                [
                                    'attribute'=>'group',
                                    'value' => function($model){
                                        $arr = ['普通用户','社区作者','后台管理'];
                                        return $arr[$model->group];
                                    }
                                ],
                                [
                                    'attribute'=>'count',
                                    'visited' => false
                                ],


                                [
                                    'attribute' => 'lasttime',
                                    'visited' => $model->lasttime ? true : false,
                                ],

                                [
                                    'attribute' => 'signip',
                                    'value' => function($model){
                                        return !empty($model->sinip) ? long2ip($model->sinip) : 0;
                                    },
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
        modal.confirm("您确定要删除该用户吗？",{
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
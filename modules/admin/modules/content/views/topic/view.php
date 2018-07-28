<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;


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
                                'name',
                                [
                                    'attribute'=>'finished',
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
                                [
                                    'attribute' => 'image',
                                    'format' => 'raw',
                                    'class' => 'ui small image',
                                    'value' => function($model){
                                        //return Yii::$app->params['imgPath']['imgUrl'] . '/' . $model->image;
                                        return Html::img(Yii::$app->params['imgPath']['imgUrl'] . '/' . $model->image,['class'=>'ui small image']);
                                    }
                                ],
                                [
                                    'label' => '包含标签',
                                    'format'=>'raw',
                                    'value' => function($model){
                                        $str = '';
                                        if($model->tags){
                                            foreach($model->tags as $tag){
                                                $str .= "<div data-id='{$tag->id}' class='ui label tag-edit'>{$tag->name}&nbsp;<i class='icon edit'></i></div>";
                                            }
                                        }else{
                                            $str = '暂无标签';
                                        }

                                        return $str;
                                    },

                                ],
                                'desc:html',
                                [
                                    'attribute' => 'user_id',
                                    'value' => function($model){
                                        return $model->user->username;
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
$delTagUrl = Url::to(['tag/ajax-delete']);
$editTagUrl = Url::to(['tag/ajax-update']);
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

    //标签编辑
    $('.tag-edit').on('click', function(){
        var that = $(this);
        var id = that.data('id');
        
        $('.ui.modal.mini').modal({
            onDeny    : function(){
                var oEdit = $('.edit-modal')
                ,oContent = null;
                
                //ajax获取编辑表单
                $.ajax({
                    url : "{$editTagUrl}",
                    type : 'get',
                    data : {'id':id,'action':'ajax'},
                    success : function(d){
                        oEdit.find('.header').html('标签编辑');
                        oContent = oEdit.find('.content');
                        
                        if(d.length > 0){
                            oContent.html(d);
                        }else{
                            oContent.html('请求数据失败，请重试。');
                        }
                        return true;
                    }
                });
                oEdit.modal('show');
                return true;
            },
            onApprove : function() {
                //ajax删除标签
                modal.confirm("您确定要删除该标签吗？",{
                    inPage:false
                },function(ele,obj){
                    $.ajax({
                        url : "{$delTagUrl}",
                        type: 'post',
                        data: {'tag_id':id},
                        success:function(d){
                            console.log(d);
                            if(d.errno === 0){
                                //删除成功
                                that.remove();
                            }
                            modal.msg(d.message);
                        }
                    });
                    return true;
                });
                return false;
                
                
            },
            selector    : {
              deny  : '.edit-btn',
              approve     : '.delete-btn'
            },
          }).modal('show')
        
    });
});

JS;
$this->registerJs($strJs);
?>
<!--选择操作-->
<div class="ui mini modal">
    <i class="close icon"></i>
    <div class="image content">
        <div class="image">
            请选择对标签的操作
        </div>
    </div>
    <div class="actions">
        <div class="ui edit-btn button">编辑</div>
        <div class="ui delete-btn button">删除</div>
    </div>
</div>
<!--编辑框-->
<div class="ui edit-modal modal">
    <i class="close icon"></i>
    <div class="header">
    </div>
    <div class="content">
    </div>
</div>

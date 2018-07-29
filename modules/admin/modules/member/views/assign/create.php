<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

?>

<div class="ui fluid container">
    <div class="ui grid">
        <div class="sixteen wide column">
            <div class="panel">
                <div class="panel-content" style="padding: 10px 15px;">
                    <div class="ui secondary">
                        <div class="ui compact menu">
                            <?php //Html::a('<i class="reply icon"></i>返回', ['index'], ['class' => 'item']) ?>
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
    <?php
    $form = ActiveForm::begin([
        'id' => 'category',
        'enableClientScript' => false,
        'enableClientValidation' => false,
        'options' => [
            'class'=>'ui form'
        ],
        'fieldConfig' => [
            'template' => '<div class="field">{label}{input}{error}{hint}</div>'
        ]

    ]);
    ?>
    <?= $form->field($model, 'id', ['options'=>['tag'=>false]])->hiddenInput([
            'id' => 'user_id'
    ])->label(false)?>
    <div class="ui grid">
        <div class="ten wide column">
            <div class="panel" style="padding-top: 20px;">
                <div class="panel-content">
                    <!--选择要分配的角色或权限-->
                    <?=
                    $form->field($model, 'username',[
                        'options'=>['tag'=>false],
                    ])->dropDownList($selectArr,[
                        'class'=>'ui search selection dropdown fluid',
                        'id'=>'search-select'
                    ]);
                    ?>
                    <div class="ui olive visible message">
                        <p>请先搜索要分配权限或角色的父节点。</p>
                    </div>
                    <div class="roles-container field">
                        <?= Html::label('可用角色')?>
                        <?= Html::checkboxList('AuthAssign[roles]',$model->roles, $roles, [
                            'id' => 'roles'
                        ])?>
                        <p>选择需要分配的角色</p>
                    </div>


                </div>
            </div>
        </div>
        <div class="six wide column">
            <div class="panel" style="padding-top: 20px;">
                <div class="panel-header">
                    <h4>分配创建说明：</h4>
                </div>
                <div class="panel-content">


                </div>
            </div>
        </div>
    </div>
    <div class="ui grid">
        <div class="sixteen wide column">
            <div class="panel">
                <div class="panel-content" style="padding: 10px 15px;">
                    <?= Html::submitButton('点击提交',['class'=>'ui mini green button','id'=>'publish_btn'])?>
                    <?= Html::resetButton('重置',['class'=>'ui mini brown disabled button'])?>
                </div>
            </div>
        </div>
    </div>
    <?php
    ActiveForm::end();
    ?>
</div>

<?php
$getRoles = Url::to(['get-roles']);
$searchItems = Yii::$app->urlManager->createAbsoluteUrl(['/admin/member/user/search','action'=>'search']);
/*css*/
$cssStr = <<<CSS
    body {padding:20px;} .help-block{color:#DB2828!important}#articleform-tags label{border:1px solid #ddd;background-color: #efefef;padding: 5px 8px;vertical-align: center;border-radius: 3px;}
CSS;
$this->registerCss($cssStr);

/*js*/
$jsStr = <<<JS
    require(['mods/modal','jSmart'],function(modal){
        //搜索下拉框
        $('#search-select').dropdown({
            minCharacters: 2,
            saveRemoteData: false,
            apiSettings: {
                url: "{$searchItems}&key={query}"
            },
            onChange:function(value, text){
                $('#user_id').val(value);
                
                $.ajax({
                    url : "{$getRoles}",
                    type : 'post',
                    data : {'id':value,'text':text},
                    success : function(d){
                        console.log(d);
                        //渲染模板
                        var tplText = $('#items_tpl').html();
                        var compiled = new jSmart(tplText);
                        
                        //填充角色
                        var roles = compiled.fetch({'data':d.data});
                        $('#roles').html(roles);
                    } 
                });
            }
            
        });
        
        
    });
JS;
$this->registerJs($jsStr);
?>
<script id="items_tpl" type="text/x-jsmart-tmpl">
{foreach $data as $val => $text}
    <label>
        <input type="checkbox" {if $text.selected == true} checked {/if} name="AuthAssign[roles][]" value="{$val}">
        {$text.value}
    </label>
{foreachelse}
    <div class="ui mini yellow message">暂无可用数据</div>
{/foreach}
</script>




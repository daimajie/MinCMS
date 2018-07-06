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
                            <?= Html::a('<i class="reply icon"></i>返回', ['index'], ['class' => 'item']) ?>
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
    <div class="ui grid">
        <div class="ten wide column">
            <div class="panel" style="padding-top: 20px;">
                <div class="panel-content">
                    <!--选择要分配的角色或权限-->
                    <?=
                    $form->field($model, 'item',[
                        'options'=>['tag'=>false],
                    ])->dropDownList($selectArr,[
                        'class'=>'ui search selection dropdown fluid',
                        'id'=>'search-select'
                    ]);
                    ?>

                    <div class="role-container">

                    </div>
                    <div class="permission-container">

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
$getRoleAndPermission = Url::to(['get-role-and-permission']);
$searchItems = Yii::$app->urlManager->createAbsoluteUrl(['/admin/rbac/item/search','action'=>'search']);
/*css*/
$cssStr = <<<CSS
    body {padding:20px;} .help-block{color:#DB2828!important}#articleform-tags label{border:1px solid #ddd;background-color: #efefef;padding: 5px 8px;vertical-align: center;border-radius: 3px;}
CSS;
$this->registerCss($cssStr);

/*js*/
$jsStr = <<<JS
    require(['mods/modal'],function(modal){
        //搜索下拉框
        $('#search-select').dropdown({
            minCharacters: 2,
            saveRemoteData: false,
            apiSettings: {
                url: "{$searchItems}&key={query}"
            },
            onChange:function(value, text){
                $.ajax({
                    url : "{$getRoleAndPermission}",
                    type : 'post',
                    data : {'name':value,'text':text},
                    success : function(d){
                        console.log(d);
                        
                        //渲染模板
                        /*var tplText = $('#tags_tpl').html();
                        var compiled = new jSmart(tplText);
                        var output = compiled.fetch({'data':d.data});
                        
                        //填充数据
                        $('#tags_container').html(output);*/
                    } 
                });
            }
        });
        
        
    });
JS;
$this->registerJs($jsStr);
?>




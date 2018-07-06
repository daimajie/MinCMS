<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

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
                    <!--标题-->
                    <?php
                    echo $form->field($model, 'name',['options'=>[
                        'tag' => false
                    ]])->textInput([
                        'placeholder' => '名称'
                    ]);

                    echo $form->field($model, 'type',['options'=>[
                        'tag' => false
                    ]])->dropDownList([
                        ''=>'请选择权限类型',
                        '1' => '角色',
                        '2' => '权限'
                    ],[
                        'id'=>'select_type'
                    ]);

                    echo $form->field($model, 'ruleName',['options'=>[
                        'tag' => false
                    ]])->textInput([
                        'placeholder' => '规则名称'
                    ]);

                    echo $form->field($model, 'data',['options'=>[
                        'tag' => false
                    ]])->textarea();


                    ?>


                </div>
            </div>
        </div>
        <div class="six wide column">
            <div class="panel" style="padding-top: 20px;">
                <div class="panel-header">
                    <h4>权限创建说明：</h4>
                </div>
                <div class="panel-content">
                    <dl>
                        <dt>
                            如果选择的权限类型是权限的话，那么名称应该写成绝对路由地址，
                        </dt>
                        <dd>
                            如： /admin/content/article/delete
                        </dd>
                        <dd>
                            代表：admin 模块 content 子模块 article 控制器 delete 方法
                        </dd>
                    </dl>
                    <dl>
                        <dt>
                            如果选择的权限类型是角色的话，那么名称应该写成通用名称，
                        </dt>
                        <dd>
                            如： 管理，编辑，作者，用户...
                        </dd>
                    </dl>

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
/*css*/
$cssStr = <<<CSS
    body {padding:20px;} .help-block{color:#DB2828!important}#articleform-tags label{border:1px solid #ddd;background-color: #efefef;padding: 5px 8px;vertical-align: center;border-radius: 3px;}
CSS;
$this->registerCss($cssStr);

/*js*/
$jsStr = <<<JS
    require(['mods/modal'],function(modal){
        $('#select_type').dropdown();
    });
JS;
$this->registerJs($jsStr);
?>




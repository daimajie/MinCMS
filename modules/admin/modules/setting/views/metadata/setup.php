<?php
use yii\helpers\Url;
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
    <div class="ui grid">
        <div class="ten wide column">
            <div class="panel" style="padding-top: 20px;">
                <div class="panel-content">
                    <?php
                    $form = ActiveForm::begin([
                        'id' => 'category',
                        'enableClientScript' => false,
                        'options' => [
                            'class'=>'ui form'
                        ],
                        'fieldConfig' => [
                            'template' => '<div class="field">{label}{input}{error}</div>'
                        ]
                    ]);
                    ?>

                    <?=
                    $form->field($model, 'name',['options'=>[
                        'tag' => false
                    ]])->textInput([
                        'placeholder' => '站点名称'
                    ]);
                    ?>
                    <?=
                    $form->field($model, 'keywords',['options'=>[
                        'tag' => false
                    ]])->textInput([
                        'placeholder' => '关键字'
                    ]);
                    ?>
                    <?=
                    $form->field($model, 'description',['options'=>[
                        'tag' => false
                    ]])->textarea([
                        'placeholder' => '站点描述'
                    ]);
                    ?>
                    <?= Html::submitButton('点击提交',['class'=>'ui green button'])?>
                    <?php
                    ActiveForm::end();
                    ?>
                </div>
            </div>
        </div>
        <div class="six wide column">
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">站点元数据设置</div>
                </div>
                <div class="panel-content">

                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerCss("body {padding:20px;} .help-block{color:#DB2828!important}");
?>
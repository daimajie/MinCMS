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
                    $form->field($model, 'site',['options'=>[
                        'tag' => false
                    ]])->textInput([
                        'placeholder' => '站点名称'
                    ]);
                    ?>
                    <?=
                    $form->field($model, 'url',['options'=>[
                        'tag' => false
                    ]])->textInput([
                        'placeholder' => '站点地址'
                    ]);
                    ?>
                    <?=
                    $form->field($model, 'sort',['options'=>[
                        'tag' => false
                    ]])->textInput([
                        'placeholder' => '排序数字'
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
                    <div class="panel-title">友情连接</div>
                </div>
                <div class="panel-content">
                    <div class="ui tiny statistics">
                        <div class="ui red  statistic">
                            <div class="value">
                                9
                            </div>
                            <div class="label">个数</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerCss("body {padding:20px;} .help-block{color:#DB2828!important}");
?>
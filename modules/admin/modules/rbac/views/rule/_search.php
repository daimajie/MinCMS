<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="menu" style="float: right;">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'enableClientScript' => false,
        'enableClientValidation' => false,
        'fieldConfig' => [
            'template' => '{input}'
        ]
    ]); ?>
    <div class="item">
        <div class="ui action input">
            <?= $form->field($model, 'name',['options'=>['tag'=>false]])->textInput([
                'placeholder' => '搜索规则名'
            ])?>
            <?= Html::submitButton('搜索', ['class' => 'ui button']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>


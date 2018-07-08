<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="right menu"  style="float: right;">
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
            <?=
            $form->field($model, 'site',['options'=>['tag'=>false]])->textInput([
                'placeholder' => '搜索站点名称'
            ])
            ?>
            <?= Html::submitButton('搜索', ['class' => 'ui button']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

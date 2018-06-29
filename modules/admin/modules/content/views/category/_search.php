<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="right menu">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'fieldConfig' => [
            'template' => '{input}'
        ]
    ]); ?>
    <div class="item">
        <div class="ui action input">
            <?= $form->field($model, 'name',['options'=>['tag'=>false]])->textInput([
                'placeholder' => '搜索分类名'
            ])?>
            <?= Html::submitButton('搜索', ['class' => 'ui button']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>


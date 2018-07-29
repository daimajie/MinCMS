<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="right menu"  style="float: right;">
    <?php $form = ActiveForm::begin([
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
            $form->field($model, 'type',['options'=>['tag'=>false]])->dropDownList([
                '' => '按类型搜索',
                '1'=>'角色',
                '2'=>'权限'
            ],[
                'class'=>'ui selection dropdown drop-select',
            ]);
            ?>
            &nbsp;
            <?=
            $form->field($model, 'name',['options'=>['tag'=>false]])->textInput([
                'placeholder' => '搜索条目名'
            ])
            ?>
            <?= Html::submitButton('搜索', ['class' => 'ui button']) ?>
        </div>
    </div>
    <?php
    ActiveForm::end();
    $this->registerJs("$('.drop-select').dropdown();");
    ?>
</div>


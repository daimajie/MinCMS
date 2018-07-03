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
            &nbsp;
            <?=
            $form->field($model, 'group',['options'=>['tag'=>false]])->dropDownList([
                '' => '按群组搜索',
                '0'=>'普通用户',
                '1'=>'社区作者',
                '2'=>'后台管理'
            ],[
                'class'=>'ui selection dropdown drop-select',
            ]);
            ?>
            &nbsp;
            <?= Html::submitButton('搜索', ['class' => 'ui button']) ?>
        </div>
    </div>
    <?php
    ActiveForm::end();
    $this->registerJs("$('.drop-select').dropdown();");
    ?>
</div>


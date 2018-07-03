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
            <?php
            echo $form->field($model, 'topic_id',[
                'options'=>['tag'=>false],
            ])->dropDownList($selectArr,[
                'class'=>'ui search selection dropdown',
                'id'=>'search-select'
            ]);
            ?>
            <?= Html::submitButton('搜索', ['class' => 'ui button']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php
$searchCats = Yii::$app->urlManager->createAbsoluteUrl(['/admin/content/topic/search','action'=>'search']);
$strJs = <<<JS
        //搜索下拉框
        $('#search-select').dropdown({
            minCharacters: 3,
            saveRemoteData: false,
            apiSettings: {
                url: "{$searchCats}&key={query}"
            },
            
        });
JS;
$this->registerJs($strJs);
?>


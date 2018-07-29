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
            <?php
            //$key = Yii::$app->request->queryParams['SearchTopic']['category_id'];
            echo $form->field($model, 'topic_id',[
                'options'=>['tag'=>false],
            ])->dropDownList($selectArr,[
                'class'=>'ui search selection dropdown',
                'id'=>'search-select'
            ]);
            ?>
            &nbsp;
            <?=
            $form->field($model, 'checked',['options'=>['tag'=>false]])->dropDownList([
                '' => '搜索状态',
                '0'=>'等待审核',
                '1'=>'审核通过'
            ],[
                'class'=>'ui selection dropdown drop-select',
            ]);
            ?>
            &nbsp;
            <?=
            $form->field($model, 'type',['options'=>['tag'=>false]])->dropDownList([
                '' => '搜索类型',
                '0'=>'原创',
                '1'=>'翻译',
                '2'=>'转载'
            ],[
                'class'=>'ui selection dropdown drop-select',
            ]);
            ?>
            &nbsp;
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
        $('.drop-select').dropdown({
        });

JS;
$this->registerJs($strJs);
?>


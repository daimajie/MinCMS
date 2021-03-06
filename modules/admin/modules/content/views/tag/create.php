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

        <div class="ui grid">
            <div class="sixteen wide column">
                <div class="panel" style="padding-top: 20px;">
                    <div class="panel-content">

                        <?php
                        $form = ActiveForm::begin([
                        'options' => [
                            'class'=>'ui form'
                        ],
                        'enableClientScript' => false,
                        'fieldConfig' => [
                            'template' => '<div class="field">{label}{input}{error}</div>'
                        ]
                        ]); ?>

                        <?php
                        //标签名
                        echo $form->field($model, 'name',['options'=>[
                            'tag' => false
                        ]])->textInput([
                            'placeholder' => '如：日期、地点'
                        ]);

                        //所属话题
                        echo $form->field($model, 'topic_id',[
                            'options'=>['tag'=>false],
                        ])->dropDownList($selectArr,[
                            'class'=>'ui search selection dropdown fluid',
                            'id'=>'search-select'
                        ]);

                        echo Html::submitInput('点击提交',['class'=>'ui green button']);

                        ?>
                        <?php
                        ActiveForm::end();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
$searchCats = Yii::$app->urlManager->createAbsoluteUrl(['/admin/content/topic/search','action'=>'search']);
$this->registerCss("body {padding:20px;} .help-block{color:#DB2828!important}");
$jsStr = <<<JS
    require(['mods/modal'],function(modal){
        //搜索下拉框
        $('#search-select').dropdown({
            minCharacters: 3,
            saveRemoteData: false,
            apiSettings: {
                url: "{$searchCats}&key={query}"
            },
            
        });
    })
JS;
$this->registerJs($jsStr);
?>
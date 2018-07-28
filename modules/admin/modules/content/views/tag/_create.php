<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

    <div class="ui fluid container">
        <div class="ui grid">
            <div class="sixteen wide column">
                <div class="panel" style="padding-top: 20px;">
                    <div class="panel-content">

                        <?php
                            $action = Yii::$app->request->get('action','get');
                            $form = ActiveForm::begin([
                            'action' => Url::current(['id'=>$model->id, 'action'=>$action]),
                            'id' => 'category',
                            'enableAjaxValidation' => true,
                            'enableClientScript' => false,
                            'options' => [
                                'class'=>'ui form'
                            ],
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

                        ?>


                        <?= Html::submitButton('点击提交',['class'=>'ui green button'])?>
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
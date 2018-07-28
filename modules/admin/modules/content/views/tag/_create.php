<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

    <div class="ui fluid container" id="wrap">
        <div class="ui grid">
            <div class="sixteen wide column">
                <div class="panel" style="padding-top: 20px;">
                    <div class="panel-content">

                        <?php
                            $form = ActiveForm::begin([
                            //'action' => Url::to(['tag/ajax-update','id'=>$model->id]),
                            'id' => 'update',
                            'enableAjaxValidation' => true,
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
                        <?php
                        ActiveForm::end();
                        ?>
                        <?= Html::submitButton('点击提交',['class'=>'ui green button','id'=>'sub-btn'])?>

                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
$updateUrl = Url::to(['tag/ajax-update', 'id'=>$model->id]);
$searchCats = Yii::$app->urlManager->createAbsoluteUrl(['/admin/content/topic/search','action'=>'search']);
$this->registerCss("body {padding:20px;} .help-block{color:#DB2828!important}");
$jsStr = <<<JS
    $('#sub-btn').on('click', function(){
        $.ajax({
            url : "{$updateUrl}",
            type : 'post',
            data : $('#update').serializeArray(),
            success : function(d){
                $('#wrap').parent().html(d);
            }
        })
    });
    require(['mods/modal'],function(modal){
        //搜索下拉框
        $('#search-select').dropdown({
            minCharacters: 3,
            saveRemoteData: false,
            apiSettings: {
                url: "{$searchCats}&key={query}"
            },
            
        });
    });
JS;
$this->registerJs($jsStr);
?>
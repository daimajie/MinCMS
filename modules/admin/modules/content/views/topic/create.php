<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

    <div class="ui fluid container">
        <div class="ui grid">
            <div class="sixteen wide column">
                <div class="panel">
                    <div class="panel-content" style="padding: 10px 15px;">
                        <div class="ui secondary  menu">
                            <div class="ui compact menu">
                                <?= Html::a('<i class="reply icon"></i>返回', ['index'], ['class' => 'item']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ui grid">
            <div class="ten wide column">
                <div class="panel" style="padding-top: 20px;">
                    <div class="panel-content">
                        <?php
                        $form = ActiveForm::begin([
                            'id' => 'category',
                            'enableClientScript' => false,
                            'options' => [
                                'class'=>'ui form'
                            ],
                            'fieldConfig' => [
                                'template' => '<div class="field">{label}{input}{error}</div>'
                            ]

                        ]);
                        ?>

                        <?=
                        $form->field($model, 'name',['options'=>[
                            'tag' => false
                        ]])->textInput([
                            'placeholder' => '如：语文、数学、大话西游'
                        ]);
                        ?>

                        <?=
                        $form->field($model, 'image',[
                            'options'=>[
                                'tag' => false,
                            ],
                            'template' =>'<div class="field dm-uploader" id="single-upload">{label}<div class="ui left action input"><div id="up_btn" class="ui button btn ">点击浏览<input type="file" name="imageFile"></div>{input}</div>{error}</div>',
                        ])->textInput([
                            'readonly'=>'',
                            'id'=>"single-upload-input"
                        ]);
                        ?>
                        <?php
                        $checked = $model->finished ? 'checked' : '';
                        if(!$model->isNewRecord):
                        ?>
                        <div class="field">
                            <div class="ui checkbox">
                                <input type="checkbox" <?= $checked?> tabindex="0" class="hidden">
                                <label>标记为已完结</label>
                            </div>
                        </div>
                        <?php
                        endif;
                        ?>
                        <?=
                        $form->field($model, 'category_id',[
                                'options'=>['tag'=>false],
                        ])->dropDownList($selectArr,[
                            'class'=>'ui search selection dropdown fluid',
                            'id'=>'search-select'
                        ]);
                        ?>

                        <?=
                        $form->field($model, 'desc',['options'=>[
                            'tag' => false
                        ]])->textarea(['rows' => 2,'class'=>false]);
                        ?>
                        <?= Html::submitButton('点击提交',['class'=>'ui green button'])?>
                        <?php
                        ActiveForm::end();
                        ?>
                    </div>
                </div>
            </div>
            <div class="six wide column">
                <div class="panel">
                    <div class="panel-header">
                        <div class="panel-title">话题统计</div>
                    </div>
                    <div class="panel-content">
                        <div class="ui tiny statistics">
                            <div class="ui red  statistic">
                                <div class="value">
                                    9
                                </div>
                                <div class="label">分类</div>
                            </div>
                            <div class="ui teal statistic">
                                <div class="value">
                                    5
                                </div>
                                <div class="label">话题</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
$upPath = Url::to(['upload']);
$searchCats = Yii::$app->urlManager->createAbsoluteUrl(['/admin/content/category/search-cats','action'=>'search']);

$this->registerCss("body {padding:20px;} .help-block{color:#DB2828!important}");
$jsStr = <<<JS
    require(['mods/modal','uploader'],function(modal){
        
        //搜索下拉框
        $('#search-select').dropdown({
            minCharacters: 3,
            saveRemoteData: false,
            apiSettings: {
                url: "{$searchCats}&key={query}"
            },
            
        });
        
        //单选框
        $('.ui.checkbox').checkbox();
        
        //文件上传
        $('#single-upload').dmUploader({ //
            url: "{$upPath}",
            maxFileSize: 3000000, // 3 Megs max
            multiple: false,
            allowedTypes: 'image/*',
            extFilter: ['jpg','jpeg','png','gif'],
            fieldName: 'imageFile',
            onUploadSuccess: function(id, data){
                if(data.errno === 0){
                    //上传成功
                    $('#single-upload-input').removeAttr('value').val(data.url);
                    //禁用上传按钮
                    $('#up_btn').addClass('disabled');
                }
                modal.alert('图片上传成功。',{inPage:false});
            }
        });
    })
JS;
$this->registerJs($jsStr);
?>
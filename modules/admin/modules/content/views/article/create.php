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
        <?php
        $form = ActiveForm::begin([
            'id' => 'category',
            'enableClientScript' => false,
            'options' => [
                'class'=>'ui form'
            ],
            'fieldConfig' => [
                'template' => '<div class="field">{label}{input}{error}{hint}</div>'
            ]

        ]);
        ?>
        <div class="ui grid">
            <div class="ten wide column">
                <div class="panel" style="padding-top: 20px;">
                    <div class="panel-content">
                        <!--标题-->
                        <?=
                        $form->field($model, 'title',['options'=>[
                            'tag' => false
                        ]])->textInput([
                            'placeholder' => '文章标题'
                        ]);
                        ?>

                        <!--简介-->
                        <?=
                        $form->field($model, 'brief',['options'=>[
                            'tag' => false
                        ]])->textarea([
                            'rows' => 3
                        ]);
                        ?>

                        <!--图片-->
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

                        <!--所属话题-->
                        <?=
                        $form->field($model, 'topic_id',[
                            'options'=>['tag'=>false],
                        ])->dropDownList($selectArr,[
                            'class'=>'ui search selection dropdown fluid',
                            'id'=>'search-select'
                        ]);
                        ?>

                        <!--类型-->
                        <?=
                        $form->field($model, 'type', ['options'=>['tag'=>false]])->radioList([
                            '0' => '原创',
                            '1' => '翻译',
                            '2' => '转载',
                        ]);
                        ?>

                        <!--推荐-->
                        <?php
                        $checked = $model->recommend === 1 ? 'checked' : '';
                        ?>
                        <div class="field">
                            <label>好文推荐</label>
                            <div class="ui checkbox">
                                <input type="checkbox" <?= $checked?> tabindex="0" class="hidden">
                                <label>推荐文章</label>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <div class="six wide column">
                <div class="panel" style="padding-top: 20px;">
                    <div class="panel-content">

                        <!--可选标签-->
                        <?=
                        $form->field($model, 'tags', ['options'=>['tag'=>false]])->checkboxList([
                                '1' => 'tag1',
                                '2' => 'tag2',
                                '3' => 'tag3',
                        ],[]);
                        ?>

                        <!--新建标签-->
                        <?=
                        $form->field($model, 'newTags', ['options'=>['tag'=>false]])->textInput([
                                'placeholder' => '新建标签,如：嘻嘻,哈哈。'
                        ])->hint('多个标签用逗号(英文)分割，最多可同时建3个标签。',[
                                'class' => 'red'
                        ]);
                        ?>

                    </div>
                </div>
            </div>
        </div>
        <div class="ui grid">
            <div class="sixteen wide column">
                <div class="panel">
                    <div class="panel-content" style="padding: 10px 15px;">
                        <!--简介-->
                        <?=
                        $form->field($model, 'content',['options'=>[
                            'tag' => false
                        ]])->textarea([
                            'rows' => 3
                        ]);
                        ?>
                        <?= Html::submitButton('点击发布',['class'=>'ui mini green button'])?>
                        <?= Html::submitButton('存为草稿',['class'=>'ui mini orange button'])?>
                        <?= Html::resetButton('重置',['class'=>'ui mini brown disabled button'])?>
                    </div>
                </div>
            </div>
        </div>
        <?php
        ActiveForm::end();
        ?>
    </div>

<?php
$upPath = Url::to(['upload']);
$searchCats = Yii::$app->urlManager->createAbsoluteUrl(['/admin/content/topic/search','action'=>'search']);

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
                modal.alert(data.message,{inPage:false});
            }
        });
    })
JS;
$this->registerJs($jsStr);
?>
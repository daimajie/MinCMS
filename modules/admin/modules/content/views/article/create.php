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
                        <?php
                        $nth1 = $model->isNewRecord || $model->type === 0 ? 'checked' : '';
                        $nth2 = $model->type === 1 ? 'checked' : '';
                        $nth3 = $model->type === 2 ? 'checked' : '';
                        ?>
                        <div class="grouped fields">
                            <label for="fruit">文章类型</label>
                            <div class="inline fields">
                                <div class="field">
                                    <div class="ui radio checkbox">
                                        <input name="ArticleForm['type']" type="radio" name="fruit" checked="" tabindex="0" class="hidden">
                                        <label>原创</label>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="ui radio checkbox">
                                        <input name="ArticleForm['type']" type="radio" name="fruit" tabindex="1" class="hidden">
                                        <label>翻译</label>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="ui radio checkbox">
                                        <input name="ArticleForm['type']" type="radio" name="fruit" tabindex="2" class="hidden">
                                        <label>转载</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--推荐-->
                        <?php
                        $checked = $model->recommend === 1 ? 'checked' : '';
                        ?>
                        <div class="field">
                            <label>好文推荐</label>
                            <div class="ui checkbox">
                                <input name="ArticleForm['recommend']" type="checkbox" <?= $checked?> tabindex="0" class="hidden">
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
                        <div class="field">
                            <label>可用标签</label>
                            <div id="tags_container">
                                <div class="ui mini message olive">请选择话题，即可显示可用标签。</div>
                            </div>

                        </div>

                        <!--新建标签-->
                        <?=
                        $form->field($model, 'newTags', [
                                'options'=>['tag'=>false],
                        ])->textInput([
                                'placeholder' => '新建标签,如：嘻嘻,哈哈。'
                        ])->hint('多个标签用逗号(英文)分割，最多可同时建3个标签。',[
                                'class' => 'help-block',
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
                            'rows' => 3,
                            'id' => 'editor'
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
$getTags = Yii::$app->urlManager->createAbsoluteUrl(['/admin/content/tag/get-tags','action'=>'gets']);

$this->registerCss("body {padding:20px;} .help-block{color:#DB2828!important}");
$jsStr = <<<JS
    require(['mods/modal'/*,'simplemdeCss'*/,'simplemde','uploader','jSmart'],function(modal/*,simplemdeCss*/,SimpleMDE){
        
        //搜索下拉框
        $('#search-select').dropdown({
            minCharacters: 3,
            saveRemoteData: false,
            apiSettings: {
                url: "{$searchCats}&key={query}"
            },
            onChange:function(value, text){
                $.ajax({
                    url : "{$getTags}",
                    type : 'post',
                    data : {'topic_id':value},
                    success : function(d){
                        console.log(d);
                        
                        //渲染模板
                        var tplText = $('#tags_tpl').html();
                        var compiled = new jSmart(tplText);
                        var output = compiled.fetch({'data':d.data});
                        
                        //填充数据
                        $('#tags_container').html(output);
                    } 
                });
            }
            
            
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
        
        //编辑器
        var simplemde2 = new SimpleMDE({
            element: $("#editor")[0],
            indentWithTabs: false,
            tabSize: 4,
            status: false,
            autosave: {
                enabled: false
            },
            spellChecker: false,
            renderingConfig: {
                codeSyntaxHighlighting: true
            }
        });
    })
JS;
$this->registerJs($jsStr);
?>
<script id="tags_tpl" type="text/x-jsmart-tmpl">
{foreach $data as $id => $name}
    <a class="ui label">
        <input type="checkbox" name="Article['tags']" id="tag_{$id}" value="{$id}">
        <label for="tag_{$id}">{$name}</label>
    </a>
{foreachelse}
    <div class="ui mini yellow message">暂无可用标签，可选择新建标签</div>
{/foreach}
</script>

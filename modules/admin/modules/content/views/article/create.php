<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\content\Tag;

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
        echo $form->field($model, 'draft',['options'=>['tag'=>false]])->textInput([
           'id'=>'id_draft',
           'type' => 'hidden',
        ])->label(false);
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
                        $form->field($model, 'type',[
                                'options' => ['tag'=>false]
                        ])->radioList([
                                '0' => '原创',
                                '1' => '翻译',
                                '2' => '转载'
                        ]);
                        ?>

                        <!--推荐-->
                        <?=
                        $form->field($model, 'recommend', [
                            'options'=>[
                                'tag'=>false
                            ],
                            'template' => '{input}'
                        ])->checkbox()->label('是否推荐此文章');
                        ?>

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
                                <?php
                                if(empty($selectTag)):
                                ?>
                                <div class="ui mini message olive">请选择话题，即可显示可用标签。</div>
                                <?php
                                else:
                                echo $form->field($model, 'tags', [
                                    'options'=>['tag'=>false],
                                    //'template' => '{input}{error}'
                                ])->checkboxList(
                                    Tag::find()
                                        ->select(['name'])
                                        ->where(['topic_id'=>$model->topic_id])
                                        ->indexBy('id')
                                        ->column()
                                )->label(false);
                                endif;
                                ?>
                            </div>
                        </div>

                        <!--新建标签-->
                        <?=
                        $form->field($model, 'newTags', [
                                'options'=>['tag'=>false],
                        ])->textInput([
                                'placeholder' => '新建标签,如：嘻嘻,哈哈。'
                        ])->hint('多个标签用逗号(英文)分割，最多可同时建3个标签。');
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
                        <?= Html::submitButton('点击发布',['class'=>'ui mini green button','id'=>'publish_btn'])?>
                        <?= Html::submitButton('存为草稿',['class'=>'ui mini orange button','id'=>'draft_btn'])?>
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
/*css*/
$cssStr = <<<CSS
    body {padding:20px;} .help-block{color:#DB2828!important}#articleform-tags label{border:1px solid #ddd;background-color: #efefef;padding: 5px 8px;vertical-align: center;border-radius: 3px;}
CSS;
$this->registerCss($cssStr);

/*js*/
$upPath = Url::to(['upload']);
$searchCats = Yii::$app->urlManager->createAbsoluteUrl(['/admin/content/topic/search','action'=>'search']);
$getTags = Yii::$app->urlManager->createAbsoluteUrl(['/admin/content/tag/get-tags','action'=>'gets']);

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
        //$('.ui.checkbox').checkbox();
        
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
        
        //存为草稿 或直接发布
        $('#draft_btn').on('click', function(){
            $('#id_draft').prop('value',1);
        });
        $('#publish_btn').on('click', function(){
            $('#id_draft').prop('value',0);
        });
    })
JS;
$this->registerJs($jsStr);
?>
<script id="tags_tpl" type="text/x-jsmart-tmpl">
<div id="articleform-tags">
{foreach $data as $id => $name}
    <label>
        <input type="checkbox" name="ArticleForm[tags][]" value="{$id}">
        {$name}
    </label>
{foreachelse}
    <div class="ui mini yellow message">暂无可用标签，可选择新建标签</div>
{/foreach}
</div>
</script>




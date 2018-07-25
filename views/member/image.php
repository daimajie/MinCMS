<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;


$defaultImage = Yii::$app->params['image'];
$this->params['hideSearch'] = true;

?>
<!--content-->
<section id="content">
    <div class="ui container">
        <div class="conts" style="overflow: hidden">
            <div class="ui column grid">
                <!--背景-->
                <div class="bg">
                    <h2 class="ui center aligned icon header" style="padding-top: 25px;">
                        <img src="<?= $user->image ? IMG_ROOT . $user->image : $defaultImage;?>" class="ui circular image">
                        <p style="color:white">
                            <?= $user->username?>
                            <small>
                                <?php
                                $tem = ['『普通用户』','『社区作者』','『后台管理』'];
                                echo $tem[$user->group];
                                ?>
                            </small>
                            <br>
                            <span style="font-size: 16px;">签名 ： <?= $user->profile? $user->profile->sign : '你好淡淡的 ～～!';?></span>
                        </p>
                    </h2>
                </div>
                <?php
                echo $this->render('_nav');  //导航
                ?>
                <div class="right-ares eleven wide column">
                    <div class="ui hidden divider"></div>
                    <!--upload-->
                    <div>
                        <img id="image" src="<?= $user->image ? IMG_ROOT.$user->image : $defaultImage?>">
                    </div>
                    <?php
                    $form = ActiveForm::begin([
                        'id' => 'avatar-form',
                        'enableClientScript' => false,
                        'options' => [
                            'class'=>'ui form'
                        ],
                        'fieldConfig' => [
                            'template' => '<div class="field">{label}{input}{error}</div>'
                        ],
                        'action' => ['member/set-avatar']

                    ]);
                    echo Html::input('hidden', 'avatar',null,['id'=>'avatar-input']);
                    ActiveForm::end();
                    ?>


                    <div id="drop-area" class="ui icon buttons">
                        <button class="ui button">
                            上传<input type="file">
                        </button>
                        <button class="ui button" id="avatar-btn">
                            确定
                        </button>
                    </div>



                    <!--/upload-->
                </div>
            </div>
        </div>
    </div>
</section>
<!--/content-->
<?php
$cssStr = <<<CSS
    #drop-area{
        position:relative;
    }
    #drop-area > button{
        width: 65px;
    }
    input[type=file] {
        position: absolute;
        left: -10px;
        top:0;
        margin: 0;
        border: solid transparent;
        width: 3px;
        opacity: 0;
        cursor: pointer;
    }
CSS;
$this->registerCss($cssStr);
$imgRoot = IMG_ROOT; //图片显示根目录
$uploadUrl = Url::to(['member/upload']);
$msg = Yii::$app->session->hasFlash('info') ? Yii::$app->session->getFlash('info') : '' ;
$jsStr = <<<JS
    require(['mods/modal','uploader'],function(modal,cropper){
            
            $('#drop-area').dmUploader({ //
                url: "{$uploadUrl}",
                maxFileSize: 3000000, // 3 Megs max
                multiple: false,
                allowedTypes: 'image/*',
                extFilter: ['jpg','jpeg','png','gif'],
                fieldName: 'imageFile',
                onUploadSuccess: function(id, data){
                    if(data.errno === 0){
                        //上传成功
                        $('#image').prop('src', "{$imgRoot}" + data.url);
                        $('#avatar-input').val(data.url);
                        
                    }
                    //modal.alert(data.message,{inPage:false});
                }
            });
            
            $('#avatar-btn').on('click', function(){
                var val = $('#avatar-input').val();
                if(val.length === 0) return;
                
                $('#avatar-form').submit();
            });
            
            
            //提示消息
            var message = "{$msg}";
            if(message.length > 0){
                modal.msg(message);
            }
    });

    
JS;
$this->registerJs($jsStr);
?>





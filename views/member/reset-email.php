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
                                if(Yii::$app->authManager->checkAccess($user->id, 'admin')){
                                    echo '『后台管理』';
                                }else{
                                    echo '『社区作者』';
                                }
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
                    <!--center-->
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
                    $form->field($model, 'email',[
                        'options'=>[
                            'tag' => false,
                        ],
                        'template' => '<div id="email_wrap" class="field">{label}<div class="ui action input">{input}<a id="send_btn" class="ui button">发送验证码</a></div>{error}</div>',
                    ])->textInput([
                        'placeholder' => '邮箱',
                        'id' => 'email_input'

                    ]);
                    ?>
                    <?=
                    $form->field($model, 'captcha',['options'=>[
                        'tag' => false
                    ]])->textInput([
                        'placeholder' => '邮箱验证码'
                    ]);
                    ?>

                    <div>
                        <?= Html::submitButton('保存',['class'=>'ui blue submit button float-l tiny'])?>
                    </div>
                    <?php
                    ActiveForm::end();
                    ?>
                    <!--/center-->
                </div>
            </div>
        </div>
    </div>
</section>
<!--/content-->
<?php
$sendEamil = Url::to(['index/send-captcha']);
//显示消息
$msg = Yii::$app->session->hasFlash('info') ? Yii::$app->session->getFlash('info') : '' ;
$jsStr = <<<JS
    require(['mods/modal'],function(modal){
        var message = "{$msg}";
        
        if(message.length > 0){
            modal.msg(message);
        }
    });


    $('#send_btn').on('click', function(){
        var msg = $('#email_wrap').find('.help-block');
        
        var val = $('#email_input').val()
        ,that = $(this);
        
        if(val.length === 0)
            return;
        msg.text('');
        
        $.ajax({
            url : "{$sendEamil}",
            type : 'post',
            data : {email : val},
            success : function(d){
                if(d.errno === 0){
                    //发送成功
                    fun.setTimer(that)
                    
                    
                }else{
                    //发送失败 显示错误消息
                    msg.text(d.message);
                    //按钮失效3秒
                    that.addClass('disabled');
                    setTimeout(function(){
                        that.removeClass('disabled');
                    },3000);
                }
            }
        });
    });

    //定时器
    var flag = 59
        ,timer=null;
    
    /*函数*/
    var fun = {
        'setTimer' : function(ele){
            clearTimeout(timer);
            ele.addClass('disabled');
            
            timer = setInterval(function(){
                ele.text('邮件已发送...' + flag--);
                if(flag < 0){
                    clearTimeout(timer);
                    ele.removeClass('disabled').text('发送验证码');
                }
            },1000);
        }
    };

    
JS;
$this->registerJs($jsStr);
?>





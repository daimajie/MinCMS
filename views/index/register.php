<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->registerCssFile('static/home/css/sign.css');
$this->params['hideSearch'] = true;

$this->title = '注册页 - ' . $this->params['name'];
?>
<section id="login">
    <div class="login">
        <h3 class="ui dividing header">
            DAIMAJIE.COM(注册页)
        </h3>
        <div class="ui grid stackable">
            <div class="ten wide column">
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
                <div class="ui form">
                    <?=
                    $form->field($model, 'username',['options'=>[
                        'tag' => false
                    ]])->textInput([
                        'placeholder' => '账号'
                    ]);
                    ?>
                    <?=
                    $form->field($model, 'password',['options'=>[
                        'tag' => false
                    ]])->passwordInput([
                        'placeholder' => '密码'
                    ]);
                    ?>
                    <?=
                    $form->field($model, 're_password',['options'=>[
                        'tag' => false
                    ]])->passwordInput([
                        'placeholder' => '重复密码'
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
                        <?= Html::submitButton('注册',['class'=>'ui blue submit button float-l tiny'])?>
                        <?= Html::a('已有账号，马上登录! ',['index/login'], ['class'=>'float-r'])?>
                    </div>





                </div>
                <?php
                ActiveForm::end();
                ?>
            </div>

            <div class="six wide column line">
                快速注册
            </div>
        </div>
    </div>
</section>
<?php
$sendEamil = Url::to(['index/send-captcha']);
$str = <<<JS
    $('#send_btn').on('click', function(){
        var val = $('#email_input').val()
        ,that = $(this);
        
        if(val.length === 0)
            return;
        
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
                    $('#email_wrap').find('.help-block').text(d.message);
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
$this->registerJs($str);
$this->registerCss('#footer{display: none;}');
?>


<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;


$this->registerCssFile('static/home/css/sign.css');
$this->params['hideSearch'] = true;
?>

<section id="login">
    <div class="login">
        <h3 class="ui dividing header">
            DAIMAJIE.COM(登录页)
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
                    <?= $form->field($model,'captcha',[
                        'options' => [
                                'tag'=>false,
                                'class' =>'inline field'
                            ],
                        'template' => '<div class="field">{label}<div class="inline field">{input}{error}</div></div>'
                    ])->widget(yii\captcha\Captcha::className(),[
                        'captchaAction'=>'index/captcha',

                        'imageOptions'=>[
                            'alt'=>'点击换图',
                            'title'=>'点击换图',
                            'style'=>'cursor:pointer',
                        ],
                        'options' => [
                            'class'=>"inline field",
                            'style' => 'vertical-align: top;',
                            'placeholder' => '验证码'
                        ],
                        'template' => '{input}{image}',
                    ]);?>
                    <?=
                    $form->field($model, 'rememberMe',['options'=>['tag' => false]])->checkbox();
                    ?>
                    <div>
                        <?= Html::submitButton('登录',['class'=>'ui blue submit button float-l tiny'])?>
                        <?= Html::a('忘记密码?',['index/forget'], ['class'=>'float-r'])?>
                    </div>
                </div>
                <?php
                ActiveForm::end();
                ?>
            </div>

            <div class="six wide column line">
                第三方登录
            </div>
        </div>
    </div>
</section>
<?php
$this->registerCss('#footer{display: none;}');
//显示消息
$msg = Yii::$app->session->hasFlash('info') ? Yii::$app->session->getFlash('info') : '' ;
$str = <<<JS
    require(['mods/modal'],function(modal){
        var message = "{$msg}";
        
        if(message.length > 0){
            modal.msg(message);
        }
    });
JS;
$this->registerJs($str);
?>
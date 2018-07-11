<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;


$this->registerCssFile('static/home/css/sign.css');
$this->params['hideSearch'] = true;
?>

<section id="login">
    <div class="login">

        <!--<h3 class="ui header">
            DAIMAJIE.COM(密码找回)
        </h3>-->
        <div class="ui grid stackable">
            <div class="ui three steps mini" style="padding:0;">
                <div class="step active">
                    <div class="content">
                        <div class="title">验证邮箱</div>
                    </div>
                </div>
                <div class="step">
                    <div class="content">
                        <div class="title">设置新密码</div>
                    </div>
                </div>
                <div class="step">
                    <div class="content">
                        <div class="title">设置成功</div>
                    </div>
                </div>
            </div>
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
                    $form->field($model, 'email',['options'=>[
                        'tag' => false
                    ]])->textInput([
                        'placeholder' => '验证邮箱'
                    ]);
                    ?>
                    <div>
                        <?= Html::submitButton('发送邮箱',['class'=>'ui blue submit button float-l tiny'])?>
                        <?= Html::a('返回登录页?',['index/login'], ['class'=>'float-r'])?>
                    </div>
                </div>
                <?php
                ActiveForm::end();
                ?>
            </div>

            <!--<div class="six wide column line">

            </div>-->
        </div>
    </div>
</section>
<?php
$this->registerCss('#footer{display: none;}');
?>
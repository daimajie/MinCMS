<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>
<section id="content">
    <div class="ui container">
        <div class="conts">
            <div class="ui stackable four column grid">
                <div class="eleven wide column">
                    <!--表单-->
                    <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

                        <div class="alert alert-success">
                            谢谢您的宝贵建议，我们会尽快给予您回复。
                        </div>

                    <?php else: ?>
                        <h4 class="ui dividing header">
                            联系我
                        </h4>
                        <div class="ui hidden divider"></div>
                        <h4 class="ui header">填写您的联系方式和建议，然后提交。</h4>

                        <p>
                            如果您有业务咨询或其他问题，请填写以下表格与我们联系。 谢谢您。
                        </p>
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
                            'placeholder' => '姓名'
                        ]);
                        ?>
                        <?=
                        $form->field($model, 'email',['options'=>[
                            'tag' => false
                        ]])->textInput([
                            'placeholder' => '邮箱'
                        ]);
                        ?>
                        <?=
                        $form->field($model, 'subject',['options'=>[
                            'tag' => false
                        ]])->textInput([
                            'placeholder' => '主题'
                        ]);
                        ?>
                        <?=
                        $form->field($model, 'body',['options'=>[
                            'tag' => false
                        ]])->textarea([
                            'placeholder' => '内容'
                        ]);
                        ?>
                        <?= $form->field($model,'captcha',[
                            'options' => [
                                'tag'=>false,
                                'class' =>'inline field'
                            ],
                            'template' => '<div class="field">{label}<div class="inline field" style="margin-bottom: 0;">{input}</div>{error}</div>'
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

                        <div>
                            <?= Html::submitButton('提交',['class'=>'ui blue submit button float-l tiny'])?>
                        </div>
                        <?php
                        ActiveForm::end();
                        ?>


                    <?php endif; ?>

                    <!--/表单-->

                    <div class="ui hidden divider"></div>

                </div>
                <div class="five wide column">
                    <!--右边栏-->
                </div>
            </div>
        </div>
    </div>
</section>



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
                    $form->field($model, 'password',['options'=>[
                        'tag' => false
                    ]])->passwordInput([
                        'placeholder' => '原始密码'
                    ]);
                    ?>
                    <?=
                    $form->field($model, 'new_password',['options'=>[
                        'tag' => false
                    ]])->passwordInput([
                        'placeholder' => '新密码'
                    ]);
                    ?>
                    <?=
                    $form->field($model, 're_password',['options'=>[
                        'tag' => false
                    ]])->passwordInput([
                        'placeholder' => '重复密码'
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
//显示消息
$msg = Yii::$app->session->hasFlash('info') ? Yii::$app->session->getFlash('info') : '' ;
$jsStr = <<<JS
    require(['mods/modal'],function(modal){
        var message = "{$msg}";
        
        if(message.length > 0){
            modal.msg(message);
        }
    });

    
JS;
$this->registerJs($jsStr);
?>





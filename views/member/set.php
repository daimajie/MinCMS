<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use app\components\helper\Helper;
use yii\widgets\ActiveForm;

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
                    $form->field($model, 'realname',['options'=>[
                        'tag' => false
                    ]])->textInput([
                        'placeholder' => '真实姓名'
                    ]);
                    ?>
                    <?=
                    $form->field($model, 'address',['options'=>[
                        'tag' => false
                    ]])->textInput([
                        'placeholder' => '位置'
                    ]);
                    ?>
                    <?=
                    $form->field($model, 'sign',['options'=>[
                        'tag' => false
                    ]])->textarea([
                        'placeholder' => '签名'
                    ]);
                    ?>
                    <?=
                    $form->field($model, 'blog',['options'=>[
                        'tag' => false
                    ]])->textInput([
                        'placeholder' => '博客'
                    ]);
                    ?>

                    <div>
                        <?= Html::submitButton('保存',['class'=>'ui blue submit button float-l tiny'])?>
                    </div>
                <?php
                ActiveForm::end();
                ?>
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



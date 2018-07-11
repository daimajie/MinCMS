<?php
use yii\helpers\Html;

$this->registerCssFile('static/home/css/sign.css');
$this->params['hideSearch'] = true;

$step = Yii::$app->request->get('step', 'sent');
?>

<section id="login">
    <div class="login">
        <div class="ui grid stackable">
            <div class="ui three steps mini" style="padding:0;">
                <div class="step <?= $step !== 'reset' && $step !== 'success' ? 'active' : '';?>">
                    <div class="content">
                        <div class="title">验证邮箱</div>
                    </div>
                </div>
                <div class="step <?= $step === 'reset' ? 'active' : '';?>">
                    <div class="content">
                        <div class="title">设置新密码</div>
                    </div>
                </div>
                <div class="step <?= $step === 'success' ? 'active' : '';?>">
                    <div class="content">
                        <div class="title">设置成功</div>
                    </div>
                </div>
            </div>
            <div>
                <?= Html::a('返回登录页。',['index/login'], ['class'=>'float-r'])?>
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
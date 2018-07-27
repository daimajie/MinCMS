<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
$this->title = $name;
?>
<section id="content">
    <div class="ui">
        <div class="conts">
            <div class="ui stackable four column grid">
                <div class="sixteen wide column">
                    <h4 class="ui dividing header">
                        <?= Html::encode('错误页') ?>
                    </h4>
                    <div class="ui hidden divider"></div>

                    <h1 class="ui header orange"><?= Html::encode($name) ?></h1>
                    <p><?= nl2br(Html::encode($message)) ?></p>
                    <p>
                        当Web服务器正在处理您的请求时发生上述错误。
                    </p>
                    <p>
                        如果您认为这是服务器错误，请与管理员联系。谢谢您
                    </p>

                    <div class="ui hidden divider"></div>

                </div>

            </div>
        </div>
    </div>
</section>
<?php
$this->registerCss("body {padding:20px;} .help-block{color:#DB2828!important}");
?>

<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;

?>



<section id="content">
    <div class="ui container">
        <div class="conts">
            <div class="ui stackable four column grid">
                <div class="sixteen wide column">
                    <h4 class="ui dividing header">
                        <?= Html::encode('错误页') ?>
                    </h4>
                    <div class="ui hidden divider"></div>

                    <?php if($exception->statusCode !== 404):?>
                        <h1 class="ui header orange"><?= Html::encode($this->title) ?></h1>
                        <p><?= nl2br(Html::encode($message)) ?></p>
                        <p>
                            当Web服务器正在处理您的请求时发生上述错误。
                        </p>
                        <p>
                            如果您认为这是服务器错误，请与我们联系。谢谢您
                        </p>
                    <?php else:?>
                        <img src="/static/home/img/404.jpeg" alt="" width="100%">
                    <?php endif;?>

                    <div class="ui hidden divider"></div>

                </div>

            </div>
        </div>
    </div>
</section>

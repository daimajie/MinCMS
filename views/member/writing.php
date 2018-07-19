<?php
use yii\helpers\Url;

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
                    <h4 class="ui header">想成为作者很简单，<a href="<?= Url::to(['site/contact'])?>">点击此处</a>，发送邮件即可。</h4>

                    <p>
                        如果您有业务咨询或其他问题，也可邮件与我们联系。 谢谢您。
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/content-->





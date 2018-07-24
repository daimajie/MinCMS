<?php
use yii\helpers\Url;
?>

<section id="content">
    <div class="ui container">
        <div class="conts">
            <div class="ui stackable four column grid">
                <div class="eleven wide column">
                    <h4 class="ui dividing header">
                        关于我
                    </h4>
                    <div class="ui hidden divider"></div>
                    <div class="ui yellow message">
                        <i class="star icon"></i>
                        <i class="close icon"></i>
                        如果有意见或建议可以 <em><a href="<?= Url::to(['site/contact'])?>">点击此处</a></em> 联系我们，万分感谢。
                    </div>
                    <div class="ui hidden divider"></div>
                    <h4 class="ui header">关于我 (about me)</h4>
                    <p><?= Yii::$app->params['about']['info']?></p>

                    <div class="ui hidden divider"></div>

                </div>
                <div class="five wide column">
                    <!--右边栏-->
                </div>
            </div>
        </div>
    </div>
</section>
<?php
$str = <<<JS
    //关闭提示信息
    $('.message .close').on('click', function() {
        $(this).closest('.message').transition('fade');
    });
JS;
$this->registerJs($str);
?>
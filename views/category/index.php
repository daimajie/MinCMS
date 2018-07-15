<?php
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;


?>
<!--content-->
<section id="content">
    <div class="ui container">
        <div class="conts">
            <div class="ui stackable four column grid">
                <div class="eleven wide column">
                    <h4 class="ui dividing header">
                        分类
                    </h4>
                    <div class="ui yellow message">
                        <i class="star icon"></i>
                        <i class="close icon"></i>
                        分类是对文章类型的划分，点击分类可以查看更多话题。
                    </div>
                    <div class="ui items">
                        <?php
                        foreach($data['categorys'] as $item):
                        ?>
                        <div class="item">
                            <a class="ui tiny image" href="<?= Url::to(['category/view', 'id'=>$item['id']]);?>">
                                <img src="<?= IMG_ROOT . $item['image']?>">
                            </a>
                            <div class="content">
                                <a class="header" href="<?= Url::to(['category/view', 'id'=>$item['id']]);?>"><?= Html::encode($item['name'])?></a>
                                <div class="description">
                                    <p><?= Html::encode($item['desc'])?></p>
                                    <div class="extra">
                                        <a href="<?= Url::to(['category/view', 'id'=>$item['id']]);?>" class="ui teal label"><i class="file outline icon"></i> 话题 ~ <?= $item['count']?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ui section divider"></div>
                        <?php
                        endforeach;
                        ?>
                    </div>


                    <div class="ui hidden divider"></div>
                    <!--pager-->
                    <?= LinkPager::widget([
                        'pagination' => $data['pagination'],
                        'options' => ['tag'=>'div','class'=>'ui pagination menu tiny'],
                        'linkContainerOptions' => ['tag'=>'dev'],
                        'linkOptions' => ['class' => 'item'],
                        'nextPageLabel' => '下一页',
                        'prevPageLabel' => '上一页',
                        'disabledPageCssClass' => 'item',
                        'disableCurrentPageButton' => true
                    ])?>
                </div>
                <div class="five wide column">
                <!--右边栏-->
                </div>
            </div>
        </div>
    </div>
</section>
<!--/content-->
<?php
$str = <<<JS
    //关闭提示信息
    $('.message .close').on('click', function() {
        $(this).closest('.message').transition('fade');
    });
JS;
$this->registerJs($str);
?>
<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\LinkPager;

?>
<!--content-->
<section id="content">
    <div class="ui container">
        <div class="conts">
            <div class="ui stackable four column grid">
                <div class="eleven wide column">
                    <h4 class="ui dividing header">
                        <?= Html::encode($category['name'])?>  ( 分类 )
                    </h4>
                    <div class="ui items">
                        <div class="item">
                            <a class="ui small image">
                                <img src="<?= IMG_ROOT . $category['image']?>">
                            </a>
                            <div class="content">
                                <h3 class="header"><?= Html::encode($category['name'])?></h3>
                                <div class="description">
                                    <?= Html::encode($category['desc'])?>
                                </div>
                                <div class="extra">
                                    <a href="javascript:;" class="ui teal label"><i class="file outline icon"></i> 收录<?= $category['count']?>篇</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ui hidden divider"></div>
                    <div class="ui yellow message">
                        <i class="star icon"></i>
                        <i class="close icon"></i>
                        您当前选择的分类是（ <?= Html::encode($category['name'])?> ）, 该分类下包含<?= $category['count']?>个话题。
                    </div>
                    <div class="ui cards topic_cards">
                        <?php
                        foreach($topics as $topic):
                        ?>
                        <div class="ui card topic_card">
                            <div class="content">
                                <i class="right floated star icon"></i>
                                <div class="header"><a href="<?= Url::to(['topic/index','id'=>$topic['id']])?>"><?= Html::encode($topic['name'])?></a></div>
                                <div class="description">
                                    <p><a href="<?= Url::to(['topic/index','id'=>$topic['id']])?>"><?= Html::encode($topic['desc'])?></a></p>
                                </div>
                            </div>
                            <div class="extra content">
                                <span class="left floated like">
                                  收录<?= $topic['count']?>文章
                                </span>
                                <span class="right floated star">
                                    <?= $topic['finished'] ? '完结' : '连载中...'; ?>
                                </span>
                            </div>
                        </div>
                        <?php
                        endforeach;
                        ?>
                    </div>

                    <div class="ui hidden divider"></div>
                    <!--pager-->
                    <?= LinkPager::widget([
                        'pagination' => $pagination,
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
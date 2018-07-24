<?php
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;

$defaultImage = Yii::$app->params['image'];
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
                            当前页面按照活跃度显示所有的社区作者，点击进入作者专区。
                        </div>
                        <!--item-->
                        <div class="ui five doubling cards">
                            <?php
                            foreach ($authors as $author):
                            ?>
                            <div class="card">
                                <div class="image">
                                    <img src="<?= $author['image']?IMG_ROOT.$author['image']:$defaultImage;?>">
                                </div>
                                <div class="extra center aligned" style="padding: 3px;">
                                    <a href="<?= Url::to(['author/view','id'=>$author['id']])?>"><?= $author['username']?></a>
                                </div>

                            </div>
                            <?php
                            endforeach;
                            ?>

                        </div>
                        <!--/item-->


                        <div class="ui hidden divider"></div>
                        <div id="pager">
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
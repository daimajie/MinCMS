<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use app\components\helper\Helper;

$defaultImage = Yii::$app->params['image'];
$this->params['keyword'] = $keyword;

$this->title = '搜索页 - ' . $this->params['name'];
?>

<!--content-->
<section id="content">
    <div class="ui container">
        <div class="conts">
            <div class="ui stackable four column grid">
                <div class="eleven wide column">
                    <h4 class="ui dividing header">
                        最新文章
                    </h4>
                    <ul class="articles">
                        <?php
                        if(!empty($articles)):
                        foreach ($articles as $article):
                            ?>
                            <li class="article <?= !empty($article['image']) ? 'have-img' : '';?>">
                                <?php if(!empty($article['image'])):?>
                                    <a class="article-img" href="<?= Url::to(['article/index', 'id'=>$article['id']])?>">
                                        <img src="<?= IMG_ROOT . $article['image'] ?>" alt="">
                                    </a>
                                <?php endif;?>
                                <div class="content">
                                    <a class="title titled" href="<?= Url::to(['article/index', 'id'=>$article['id']])?>">
                                        <?= Html::encode($article['title']);?>
                                    </a>
                                    <p class="abstract">
                                        <?= Helper::truncate_utf8_string(Html::encode($article['brief']),77);?>
                                    </p>
                                    <div class="meta">
                                        <img class="source-profile ui avatar image" src="<?= $article['user']['image'] ? IMG_ROOT . $article['user']['image'] : $defaultImage;?>">
                                        <a class="nickname linked" href="<?= Url::to(['author/view','id'=>$article['user_id']])?>"><?= $article['user']['username']?></a>
                                        <span><?= $article['comment']?>评</span>
                                        <span><?= date('Y-m-d', $article['created_at'])?></span>
                                        <a href="<?= Url::to(['topic/index', 'id'=>$article['topic_id']])?>" class="wemedia-icon topic"><?= $article['topic']['name']?></a>
                                    </div>
                                </div>
                            </li>
                        <?php
                        endforeach;
                        else:
                            echo '没有相关数据。';
                        endif;
                        ?>
                    </ul>
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

                </div>
            </div>
        </div>
    </div>
</section>
<!--/content-->


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
                        最新文章
                    </h4>
                    <ul class="articles">
                        <?php
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
                                    <?= Html::encode($article['brief']);?>
                                </p>
                                <div class="meta">
                                    <img class="source-profile ui avatar image" src="static/home/img/photo.jpeg">
                                    <a class="nickname linked" target="_blank" href="#">萌神木木</a>
                                    <span><?= $article['comment']?>评</span>
                                    <span><?= Yii::$app->formatter->asRelativeTime($article['created_at'])?></span>
                                    <a target="_blank" href="<?= Url::to(['topic/index', 'id'=>$article['topic_id']])?>" class="wemedia-icon topic"><?= $article['topic']['name']?></a>
                                </div>
                            </div>
                        </li>
                        <?php
                        endforeach;
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
                    <!--活跃作者-->
                    <!--<div class="side-bar active">
                        <h4 class="ui dividing header">
                            动态
                        </h4>
                        <div class="ui feed">
                            <div class="event">
                                <div class="label">
                                    <img src="static/home/img/avatar.jpg">
                                </div>
                                <div class="content">
                                    <div class="summary">
                                        <a class="user">
                                            Elliot Fu
                                        </a> added you as a friend
                                        <div class="date">
                                            1 Hour Ago
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ui feed">
                                <div class="event">
                                    <div class="label">
                                        <i class="pencil icon"></i>
                                    </div>
                                    <div class="content">
                                        <div class="summary">
                                            You posted on your friend <a>Stevie Feliciano's</a> wall.
                                            <div class="date">Today</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="event">
                                <div class="label">
                                    <img src="static/home/img/avatar.jpg">
                                </div>
                                <div class="content">
                                    <div class="summary">
                                        <a>Helen Troy</a> added <a>2 new illustrations</a>
                                        <div class="date">
                                            4 days ago
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="event">
                                <div class="label">
                                    <img src="static/home/img/avatar.jpg">
                                </div>
                                <div class="content">
                                    <div class="summary">
                                        <a class="user">
                                            Jenny Hess
                                        </a> added you as a friend
                                        <div class="date">
                                            2 Days Ago
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="event">
                                <div class="label">
                                    <img src="static/home/img/avatar.jpg">
                                </div>
                                <div class="content">
                                    <div class="summary">
                                        <a>Joe Henderson</a> posted on his page
                                        <div class="date">
                                            3 days ago
                                        </div>
                                    </div>
                                    <div class="extra text">
                                        阿斯顿
                                    </div>
                                </div>
                            </div>
                            <div class="event">
                                <div class="label">
                                    <img src="static/home/img/avatar.jpg">
                                </div>
                                <div class="content">
                                    <div class="summary">
                                        <a>Justen Kitsune</a> added <a>2 new photos</a> of you
                                        <div class="date">
                                            4 days ago
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>-->
                    <!--热门文章-->
                    <div class="side-bar hot">
                        <h4 class="ui dividing header">
                            热门文章
                        </h4>
                        <ol class="ui list">
                            <?php foreach($hot as $item):?>
                            <li>
                                <a class="linked" href="<?= Url::to(['article/index','id'=>$item['id']])?>">
                                    <?= Html::encode($item['title'])?>
                                </a>
                            </li>
                            <?php endforeach;?>
                        </ol>
                    </div>
                    <!--topics-->
                    <div class="side-bar topics">
                        <h4 class="ui dividing header">
                            热门话题
                        </h4>
                        <?php foreach ($hotTopic as $topic):?>
                        <a class="ui image label" href="<?= Url::to(['topic/index', 'id'=>$topic['id']])?>">
                            <img src="<?= IMG_ROOT . $topic['image']?>">
                            <?= $topic['name']?>
                        </a>
                        <?php endforeach;?>
                    </div>
                    <!--友链-->
                    <div class="side-bar friend">
                        <h4 class="ui dividing header">
                            友情链接
                        </h4>
                        <div class="ui horizontal list link">
                            <?php foreach ($friends as $friend):?>
                            <a class="item" target="_blank" href="<?= $friend['url']?>"><?= Html::encode($friend['site'])?></a>
                            <?php endforeach;?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<!--/content-->


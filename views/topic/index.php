<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use app\components\helper\Helper;


$defaultImage = Yii::$app->params['image']; //默认头像
?>
<!--content-->
<section id="content">
    <div class="ui container">
        <div class="conts">
            <div class="ui stackable four column grid">
                <div class="eleven wide column">
                    <h4 class="ui dividing header">
                       <?= Html::encode($topic['name'])?> ( 话题 )
                    </h4>
                    <ul class="articles">
                        <?php
                        foreach ($articles as $article):
                        ?>
                        <li class="article <?= !empty($article['image']) ? 'have-img' : '';?>">
                            <?php
                            if(!empty($article['image'])):
                            ?>
                            <a class="article-img" href="<?= Url::to(['article/index', 'id'=>$article['id']])?>">
                                <img src="<?= IMG_ROOT . $article['image']?>">
                            </a>
                            <?php
                            endif;
                            ?>
                            <div class="content">
                                <a class="title titled" href="<?= Url::to(['article/index', 'id'=>$article['id']])?>">
                                    <?= Html::encode($article['title'])?>
                                </a>
                                <p class="abstract">
                                    <?= Helper::truncate_utf8_string(Html::encode($article['brief']), 77)?>
                                </p>
                                <div class="meta">
                                    <img class="source-profile ui avatar image" src="<?= $article['user']['image'] ? $article['user']['image'] : $defaultImage ;?>">
                                    <a class="nickname" target="_blank" href="javascript:;"><?= $article['user']['username'] ?></a>
                                    <span><?= $article['comment']?>评</span>
                                    <span><?= date('Y-m-d H:i:s', $article['created_at'])?></span>
                                    <a href="<?= Url::to(['topic/index', 'id'=>$article['topic']['id']])?>" class="wemedia-icon"><?= $article['topic']['name']?></a>
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
                    <!--话题信息-->
                    <div class="side-bar info">
                        <h4 class="ui dividing header">
                            当前话题
                        </h4>
                        <div class="ui items">
                            <div class="item">
                                <div class="content">
                                    <a class="header"><?= Html::encode($topic['name'])?></a>
                                    <div class="description">
                                        <p><?= Html::encode($topic['desc'])?></p>
                                        <div class="extra">
                                            <?= $topic['finished'] == 1 ? '<div class="ui green label">完结</div>' : '<div class="ui green label">连载中...</div>';?>
                                            <div class="ui yellow label"><i class="file outline icon"></i> 收录<?= $topic['count']?>篇</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--云标签-->
                    <div class="side-bar tags">
                        <h4 class="ui dividing header">
                            话题标签
                        </h4>
                        <a href="<?= Url::current(['tag' => null])?>" class="ui tag label">全部文章</a>
                        <?php
                        foreach ($topic['tags'] as $tag):
                        ?>
                        <a href="<?= Url::current(['tag'=>$tag['id']])?>" class="ui tag label"><?= Html::encode($tag['name'])?></a>
                        <?php
                        endforeach;
                        ?>
                        <a href="<?= Url::current(['tag' => 'unset'])?>" class="ui tag label">未设标签</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<!--/content-->
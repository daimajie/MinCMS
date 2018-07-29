<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\helpers\Html;
use app\components\helper\Helper;

$defaultImage = Yii::$app->params['image'];
$this->params['hideSearch'] = true;

$this->title = $user->username . ' - 作者中心 - ' . $this->params['name'];
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
                                if(Yii::$app->authManager->checkAccess($user->id, 'admin')){
                                    echo '『后台管理』';
                                }else{
                                    echo '『社区作者』';
                                }
                                ?>
                            </small>
                            <br>
                            <span style="font-size: 16px;">签名 ： <?= $user->profile? $user->profile->sign : '你好淡淡的 ～～!';?></span>
                        </p>
                    </h2>
                </div>

                <!--文章列表-->
                <div class="ui stackable grid container">
                    <div class="eleven wide column">
                        <!--content-->
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
                                            <?= Helper::truncate_utf8_string(Html::encode($article['brief']),77);?>
                                        </p>
                                        <div class="meta">
                                            <img class="source-profile ui avatar image" src="<?= $article['user']['image'] ? $article['user']['user'] : $defaultImage;?>">
                                            <a class="nickname linked" target="_blank" href="javascript:;"><?= $article['user']['username']?></a>
                                            <span><?= $article['comment']?>评</span>
                                            <span><?= date('Y-m-d', $article['created_at'])?></span>
                                            <a href="<?= Url::to(['topic/index', 'id'=>$article['topic_id']])?>" class="wemedia-icon topic"><?= $article['topic']['name']?></a>
                                        </div>
                                    </div>
                                </li>
                            <?php
                            endforeach;
                            ?>
                        </ul>
                        <!--/content-->
                        <!--pager-->
                        <div class="ui hidden divider"></div>
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
                        <div class="ui hidden divider"></div>
                        <!--右边栏-->
                        
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
<!--/content-->



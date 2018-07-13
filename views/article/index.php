<?php
use app\components\helper\Helper;
use yii\helpers\Html;
use yii\helpers\Url;

$this->params['hideSearch'] = true;
$defaultImage = Yii::$app->params['image'];
?>


<!--content-->
<section id="content">
    <div class="ui container">
        <div class="conts">
            <div class="ui stackable four column grid">
                <div class="eleven wide column">
                    <h4 class="ui dividing header">
                        文章 - <small><?= Helper::truncate_utf8_string(Html::encode($article['title']), 16)?></small>
                    </h4>
                    <div class="ui hidden divider"></div>

                    <!--文章内容-->
                    <div class="article-content">
                        <!--标题-->
                        <h2 class="ui header">
                            <?= Html::encode($article['title'])?>
                        </h2>
                        <div class="ui hidden divider"></div>

                        <!--信息-->
                        <div class="meta margin-b-10">
                            <img class="source-profile ui avatar image" src="<?= $article['user']['image'] ? $article['user']['image']: $defaultImage;?>">
                            <a class="nickname" href="javascript:;"><?= $article['user']['username']?></a>
                            <span><?= $article['comment']?>评</span>
                            <span><?= date('Y-m-d H:i:s',$article['created_at'])?></span>
                            <?php
                            $type = ['原创','翻译','转载'];
                            $class = ['wemedia-green','wemedia-purple','wemedia-black'];
                            echo '<span class="wemedia-icon '. $class[$article['type']] .'">'. $type[$article['type']] .'</span>';
                            ?>
                            <a href="<?= Url::to(['topic/index','id'=>$article['topic_id']])?>" class="wemedia-icon"><?= $article['topic']['name']?></a>
                        </div>
                        <br>
                        <div class="meta margin-b-10">
                            <span>字数~<?= $article['words']?></span>
                            <span>阅读~<?= $article['visited']?></span>
                            <span>喜欢~<?= $article['likes']?></span>
                            <span>收藏~<?= $article['collect']?></span>
                        </div>
                        <div class="ui hidden divider"></div>

                        <!--内容-->
                        <div class="art-content">
                            <p><?= $article['content']['content']?></p>
                        </div>
                        <div class="ui hidden divider"></div>

                        <!--动作-->
                        <div class="art-action">
                            <!--标签-->
                            <div class="art-tags margin-b-10 float-r">
                                <?php
                                foreach($article['tags'] as $tag):
                                ?>
                                <a class="ui tag label"><?= $tag['name']?></a>
                                <?php
                                endforeach;
                                ?>
                            </div>
                            <!--分享-->
                            <div class="action float-l">
                                <button class="ui circular facebook icon button">
                                    <i class="facebook icon"></i>
                                </button>
                                <button class="ui circular twitter icon button">
                                    <i class="twitter icon"></i>
                                </button>
                                <button class="ui circular linkedin icon button">
                                    <i class="linkedin icon"></i>
                                </button>
                                <button class="ui circular google plus icon button">
                                    <i class="google plus icon"></i>
                                </button>
                            </div>
                        </div>
                        <div class="ui hidden divider"></div>

                        <!--喜欢收藏-->
                        <div class="like-collect">
                            <div class="ui buttons ">
                                <button id="likes_btn" class="ui pink  button <?= $isCollect['likes'] ? 'disabled' : '' ;?>">喜欢</button>
                                <div class="or"></div>
                                <button id="collect_btn" class="ui positive button <?= $isCollect['collect'] ? 'disabled' : '' ;?>">收藏</button>
                            </div>
                        </div>
                        <div class="ui hidden divider"></div>

                        <!--上下一篇-->
                        <div class="prevNext margin-b-10">
                            <button class="ui compact grey basic tiny button">上一篇</button>
                            <span><a class="linked fs-16" href="<?= $prevNext['prev']['url']?>"><?= $prevNext['prev']['title']?></a></span>
                            <div class="margin-b-10"></div>
                            <button class="ui compact grey basic tiny button">下一篇</button>
                            <span><a class="linked fs-16" href="<?= $prevNext['next']['url']?>"><?= $prevNext['next']['title']?></a></span>
                        </div>
                    </div>
                    <div class="ui hidden divider"></div>

                    <!--评论-->
                    <div class="ui threaded comments">
                        <h3 class="ui dividing header">评论 <?= $article['comment']?></h3>
                        <form class="ui reply form">
                            <div class="field pane-wrap">
                                <textarea <?= Yii::$app->user->isGuest ? 'disabled="disabled"' : ''; ?> placeholder="说点什么呢？"></textarea>
                                <?php if(Yii::$app->user->isGuest):?>
                                <div class="pane">
                                    <div class="pane-btn">
                                        <i class="right arrow icon"></i> <a href="<?= Url::to(['index/login'])?>">马上登录</a>
                                    </div>
                                </div>
                                <?php endif;?>
                            </div>
                            <div class="ui blue labeled submit icon button <?= Yii::$app->user->isGuest ? 'disabled' : ''; ?>">
                                <i class="icon edit"></i> 提交评论
                            </div>
                        </form>
                        <div class="ui hidden divider"></div>
                        <div class="comment">
                            <a class="avatar">
                                <img src="static/home/img/avatar.jpg">
                            </a>
                            <div class="content">
                                <a class="author">Matt</a>
                                <div class="metadata">
                                    <span class="date">Today at 5:42PM</span>
                                </div>
                                <div class="text">
                                    How artistic!
                                </div>
                                <div class="actions">
                                    <a class="reply">回复</a>
                                </div>
                            </div>
                        </div>
                        <div class="comment">
                            <a class="avatar">
                                <img src="static/home/img/avatar.jpg">
                            </a>
                            <div class="content">
                                <a class="author">Elliot Fu</a>
                                <div class="metadata">
                                    <span class="date">Yesterday at 12:30AM</span>
                                </div>
                                <div class="text">
                                    <p>This has been very useful for my research. Thanks as well!</p>
                                </div>
                                <div class="actions">
                                    <a class="reply">回复</a>
                                </div>
                            </div>
                            <div class="comments">
                                <div class="comment">
                                    <a class="avatar">
                                        <img src="static/home/img/avatar.jpg">
                                    </a>
                                    <div class="content">
                                        <a class="author">Jenny Hess</a>
                                        <div class="metadata">
                                            <span class="date">Just now</span>
                                        </div>
                                        <div class="text">
                                            Elliot you are always so right :)
                                        </div>
                                        <div class="actions">
                                            <a class="reply">回复</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="comment">
                            <a class="avatar">
                                <img src="static/home/img/avatar.jpg">
                            </a>
                            <div class="content">
                                <a class="author">Joe Henderson</a>
                                <div class="metadata">
                                    <span class="date">5 days ago</span>
                                </div>
                                <div class="text">
                                    Dude, this is awesome. Thanks so much
                                </div>
                                <div class="actions">
                                    <a class="reply">回复</a>
                                </div>
                            </div>
                        </div>
                        <div class="comment">
                            <a class="avatar">
                                <img src="static/home/img/avatar.jpg">
                            </a>
                            <div class="content">
                                <a class="author">Elliot Fu</a>
                                <div class="metadata">
                                    <span class="date">Yesterday at 12:30AM</span>
                                </div>
                                <div class="text">
                                    <p>This has been very useful for my research. Thanks as well!</p>
                                </div>
                                <div class="actions">
                                    <a class="reply">回复</a>
                                </div>
                            </div>
                            <div class="comments">
                                <div class="comment">
                                    <a class="avatar">
                                        <img src="static/home/img/avatar.jpg">
                                    </a>
                                    <div class="content">
                                        <a class="author">Jenny Hess</a>
                                        <div class="metadata">
                                            <span class="date">Just now</span>
                                        </div>
                                        <div class="text">
                                            Elliot you are always so right :)
                                        </div>
                                        <div class="actions">
                                            <a class="reply">回复</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="comment">
                            <a class="avatar">
                                <img src="static/home/img/avatar.jpg">
                            </a>
                            <div class="content">
                                <a class="author">Joe Henderson</a>
                                <div class="metadata">
                                    <span class="date">5 days ago</span>
                                </div>
                                <div class="text">
                                    Dude, this is awesome. Thanks so much
                                </div>
                                <div class="actions">
                                    <a class="reply">回复</a>
                                </div>
                            </div>
                        </div>
                        <div class="comment">
                            <a class="avatar">
                                <img src="static/home/img/avatar.jpg">
                            </a>
                            <div class="content">
                                <a class="author">Joe Henderson</a>
                                <div class="metadata">
                                    <span class="date">5 days ago</span>
                                </div>
                                <div class="text">
                                    Dude, this is awesome. Thanks so much
                                </div>
                                <div class="actions">
                                    <a class="reply">回复</a>
                                </div>
                            </div>
                        </div>
                        <div class="ui hidden divider"></div>
                        <!--pager-->
                        <div class="ui pagination menu tiny">
                            <a class="item active">
                                1
                            </a>
                            <div class="disabled item">
                                ...
                            </div>
                            <a class="item">
                                10
                            </a>
                            <a class="item">
                                11
                            </a>
                            <a class="item">
                                12
                            </a>
                        </div>

                    </div>

                </div>
                <div class="five wide column">

                    <!--作者-->
                    <div class="side-bar author">
                        <h4 class="ui dividing header">
                            作者
                        </h4>
                        <div class="ui feed">
                            <div class="event">
                                <div class="label">
                                    <img src="<?= $article['user']['image']?$article['user']['image']:$defaultImage;?>">
                                </div>
                                <div class="content">
                                    <div class="summary">
                                        作者 ： <?= $article['user']['username']?>
                                    </div>
                                    <div class="summary">
                                        活跃 ： <?= Yii::$app->formatter->asRelativeTime($article['user']['lasttime'])?>
                                    </div>
                                    <div class="summary">
                                        提交 ： <?= $article['user']['count']?> 篇文章
                                    </div>
                                </div>
                            </div>
                            <!--<div class="event">
                                <div class="label">
                                    <i class="pencil icon "></i>
                                </div>
                                <div class="content">
                                    <div class="summary">
                                        签名 ： 时间是一切财富中最宝贵的财富。
                                        <div class="date">
                                            —— 2018-12-12
                                        </div>
                                    </div>
                                </div>
                            </div>-->
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</section>
<!--/content-->
<?php
$collectUrl = Url::to(['article/collect']);
$likesUrl = Url::to(['article/likes']);
$jsStr = <<<JS
    require(['mods/modal'],function(modal){
        //添加喜欢
        $('#collect_btn').on('click',function(){
            var that = $(this);
            $.ajax({
                url : "{$collectUrl}",
                type : 'post',
                data : {article_id:"{$article['id']}"},
                success : function(d){
                    if(d.errno === 0){
                        //添加成功
                        that.addClass('disabled');
                    }
                    modal.msg(d.message);
                }
            });
        });
        //添加收藏
        $('#likes_btn').on('click',function(){
            var that = $(this);
            $.ajax({
                url : "{$likesUrl}",
                type : 'post',
                data : {article_id:"{$article['id']}"},
                success : function(d){
                    if(d.errno === 0){
                        //添加成功
                        that.addClass('disabled');
                    }
                    modal.msg(d.message);
                }
            });
        });
    });
JS;
$this->registerJs($jsStr);
?>
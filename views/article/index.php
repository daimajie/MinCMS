<?php
use app\components\helper\Helper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

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
                            <!--share-->
                            <div class="F_share R">
                                <div class="bdsharebuttonbox">
                                    <a class="bds_qzone" data-cmd="qzone" href="#"></a>
                                    <a class="bds_tsina" data-cmd="tsina"></a>
                                    <a class="bds_weixin" data-cmd="weixin"></a>
                                    <a class="bds_tqq" data-cmd="tqq"></a>
                                </div>
                            </div>
                            <script>
                                window._bd_share_config = {
                                    common: {
                                        bdText: "<?= $article['title']?>",
                                        bdDesc: "<?= $article['brief']?>",
                                        bdUrl: "<?= Yii::$app->urlManager->createAbsoluteUrl(['article/index','id'=>$article['id']]);?>",
                                        bdPic: "<?= $article['image']? Yii::$app->params['domain'] . IMG_ROOT . $article['image'] : '' ;?>"
                                    },
                                    share: [{
                                        "bdSize": 32
                                    }],
                                    /*slide: [{
                                        bdImg: 0,
                                        bdPos: "right",
                                        bdTop: 100
                                    }],*/
                                    image: [{
                                        viewType: 'list',
                                        viewPos: 'top',
                                        viewColor: 'black',
                                        viewSize: '32',
                                        viewList: ['qzone', 'tsina', 'weixin', 'tqq']
                                    }],
                                    /*selectShare: [{
                                        "bdselectMiniList": ['qzone', 'tsina', 'weixin', 'tqq']
                                    }]*/
                                }
                                with (document) 0[(getElementsByTagName('head')[0] || body).appendChild(createElement('script')).src = 'http://bdimg.share.baidu.com/static/api/js/share.js?cdnversion=' + ~(-new Date() / 36e5)];
                            </script>
                            <!--/share-->
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
                                <textarea id="comment_val" <?= Yii::$app->user->isGuest ? 'disabled="disabled"' : ''; ?> placeholder="说点什么呢？"></textarea>
                                <?php if(Yii::$app->user->isGuest):?>
                                <div class="pane">
                                    <div class="pane-btn">
                                        <i class="right arrow icon"></i> <a href="<?= Url::to(['index/login'])?>">马上登录</a>
                                    </div>
                                </div>
                                <?php endif;?>
                            </div>
                            <div id="comment_btn" class="ui blue labeled submit icon button <?= Yii::$app->user->isGuest ? 'disabled' : ''; ?>">
                                <i class="icon edit"></i> 提交评论
                            </div>
                        </form>
                        <div class="ui hidden divider"></div>
                        <div id="comment_container">
                            <?php
                            foreach($comments as $comment):
                                ?>
                                <div class="comment">
                                    <a class="avatar ui medium circular image">
                                        <img src="<?= $comment['user']['image'] ? IMG_ROOT.$comment['user']['image']:$defaultImage?>">
                                    </a>
                                    <div class="content">
                                        <a class="author"><?= $comment['user']['username']?></a>
                                        <div class="metadata">
                                            <span class="date"><?= Yii::$app->formatter->asRelativeTime($comment['created_at'])?></span>
                                        </div>
                                        <div class="text">
                                            <?= $comment['content']?>
                                        </div>
                                        <div class="actions">
                                            <a class="reply">回复</a>
                                            <?php if(!Yii::$app->user->isGuest && $comment['user_id'] == Yii::$app->user->id):?>
                                                <a data-id="<?= $comment['id']?>" class="delete">删除</a>
                                            <?php endif;?>
                                        </div>
                                        <?php
                                        if(!empty($comment['replys'])):
                                            foreach ($comment['replys'] as $reply):
                                                ?>
                                                <div class="comments">
                                                    <div class="comment">
                                                        <a class="avatar ui medium circular image">
                                                            <img src="<?= $reply['user']['image'] ? IMG_ROOT.$reply['user']['image']:$defaultImage;?>">
                                                        </a>
                                                        <div class="content">
                                                            <a class="author"><?= $reply['user']['username']?></a>
                                                            <div class="metadata">
                                                                <span class="date"><?= Yii::$app->formatter->asRelativeTime($reply['created_at'])?></span>
                                                            </div>
                                                            <div class="text">
                                                                <?= $reply['content']?>
                                                            </div>
                                                            <div class="actions">
                                                                <a class="reply">回复</a>
                                                                <?php if(!Yii::$app->user->isGuest && $reply['user_id'] == Yii::$app->user->id):?>
                                                                    <a data-id="<?= $reply['id']?>" class="delete">删除</a>
                                                                <?php endif;?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </div>
                                </div>
                            <?php
                            endforeach;
                            ?>
                        </div>


                        <div class="ui hidden divider"></div>
                        <!--pager-->
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
                            <div class="event">
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
                            </div>
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
$commentUrl = Url::to(['comment/comment']);
$getComments = Url::to(['comment/comments','id'=>$article['id']]);
$deleteComment = Url::to(['comment/delete','id'=>$article['id']]);
$jsStr = <<<JS
    require(['mods/modal','jSmart'],function(modal){
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
        
        //ajax分页
        $('#pager').on('click', 'a', function(){
            var that = $(this);
            var url = that.attr('href');
            
            
            
            //刷新评论区
            refreshComment(url);
            //阻止跳转
            return false;
        });
        
        //提交评论
        $('#comment_btn').on('click', function(){
            var that = $(this)
                ,input = $('#comment_val');
            var val = input.val();
            
            if(val.length <= 0)
                return;
            
            //发送请求
            $.ajax({
                url : "{$commentUrl}",
                type : 'post',
                data : {id : "{$article['id']}", content : val},
                success : function(d){
                    //console.log(d);
                    if(d.errno === 0){
                        //评论成功
                        //清空输入框
                        input.val('');
                        //刷新评论列表
                        refreshComment("$getComments");
                    }
                    modal.msg(d.message);
                    
                }
            });
        });
        
        //评论删除
        $('#comment_container').on('click', 'a.delete', function(){
            var that = $(this);
            var id = that.data('id');
            
            if(id <= 0)return;
            
            modal.confirm("确定要删除该评论吗？",{
                inPage:false
            },function(ele,obj){
                $.ajax({
                    url : "{$deleteComment}",
                    type : 'post',
                    data : {id:id},
                    success : function(d){
                        if(d.errno === 0){
                            //删除成功
                            //刷新评论列表
                            refreshComment("$getComments");
                        }
                        modal.msg(d.message);
                    }
                });
                return true;
            });
            return false;
            
        });
        
        
    });

    //刷新评论列表
    function refreshComment(url){
        $.ajax({
            url:url,
            type : 'get',
            success : function(d){
                if(d.errno === 0){
                    //渲染评论列表
                    var tplText = $('#comments_tpl').html();
                    var compiled = new jSmart(tplText);
                    var output = compiled.fetch({'data':d.data.comments});
                    
                    //填充数据
                    $('#comment_container').html(output);
                    
                    //渲染分页
                    $('#pager').html(d.data.pagination);
                }
                // modal.msg(d.message);
                //console.log(d);
            }
        });
    }
JS;
$this->registerJs($jsStr);
?>
<script id="comments_tpl" type="text/x-jsmart-tmpl">
{foreach $data as $key => $comment}
<div class="comment">
    <a class="avatar ui medium circular image">
        <img src="{$comment.user.image}">
    </a>
    <div class="content">
        <a class="author">{$comment.user.username}</a>
        <div class="metadata">
            <span class="date">{$comment.created_at}</span>
        </div>
        <div class="text">
            <p>{$comment.content}</p>
        </div>
        <div class="actions">
            <a class="reply">回复</a>
            {if $comment.isself}<a data-id="{$comment.id}" class="delete">删除</a>{/if}
        </div>
    </div>
    {foreach $comment.replys as $k => $reply}
    <div class="comments">
        <div class="comment">
            <a class="avatar ui medium circular image">
                <img src="{$reply.user.image}">
            </a>
            <div class="content">
                <a class="author">{$reply.user.username}</a>
                <div class="metadata">
                    <span class="date">{$reply.created_at}</span>
                </div>
                <div class="text">
                    {$reply.content}
                </div>
                <div class="actions">
                    <a class="reply">回复</a>
                    {if $comment.isself}<a data-id="{$reply.id}" class="delete">删除</a>{/if}
                </div>
            </div>
        </div>
    </div>
    {/foreach}
</div>
{/foreach}
</script>
<!--<script>
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
</script>-->
<?php
use yii\helpers\Html;
use app\assets\SemanticUIAsset;
use app\assets\RequireJsAsset;
use app\assets\HomeAsset;
use app\assets\Html5Asset;
use yii\widgets\Menu;

SemanticUIAsset::register($this);
//RequireJsAsset::register($this);
HomeAsset::register($this);
Html5Asset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="favicon.ico">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>


</head>
<body>
<?php $this->beginBody() ?>
<!--top-nav-->
<section id="top">
    <!--导航-->
    <div class="main-nav">
        <div class="ui container">
            <div class="ui inverted menu navs">
                <div class="item">
                    <a href="/">DAIMAJIE.COM</a>
                </div>
                <a class="yellow item active">首页</a>
                <a class="olive item">话题</a>
                <a class="green item">随笔</a>
                <a class="teal item">关于我</a>

                <div class="right menu">
                    <!--登录状态-->
                    <div class="ui dropdown item top right pointing">
                        <div class="text">
                            <img class="ui avatar image" src="static/home/img/avatar.jpg">
                            珍妮赫斯
                            <i class="dropdown icon"></i>
                        </div>
                        <div class="menu">
                            <a class="item">个人中心</a>
                            <a class="item">写作</a>
                            <a class="item">退出<i class="sign out icon"></i></a>
                        </div>
                    </div>
                    <!--游客状态-->
                    <!--<div class="item">
                        <div class="ui mini icon buttons">
                            <button class="ui teal button">
                                <i class="sign in icon"></i>
                                登录
                            </button>
                            <button class="ui brown button">
                                注册
                            </button>
                        </div>
                    </div>-->
                </div>
            </div>
        </div>
    </div>
    <!--搜索-->
    <?php
    if(!isset($this->params['hideSearch'])):
    ?>
    <div class="search ui container">
        <div class="search-wrap">
            <form name="formSearch" action="/" method="post">
                <div class="input-search">
                    <div class="icon-search"><img src="https://static3w.kuaikanmanhua.com/static/img/search_5e7251b.png"></div>

                    <input type="text" id="txtKey" name="keyword" class="txt" placeholder="搜索文章标题" autocomplete="off" value="">
                    <input type="submit" name="button" id="btnSearch" value="搜索" class="btn">
                </div>
            </form>
        </div>
        <div class="search-cover"></div>
    </div>
    <?php
    endif;
    ?>
</section>
<!--/top-nav-->

<!--content-->
<?= $content?>
<!--/content-->

<!--footer-->
<secation id="footer">
    <div class="ui inverted vertical footer segment">
        <div class="ui container">
            <div class="ui stackable inverted divided equal height stackable grid">
                <div class="three wide column">
                    <h4 class="ui inverted header">关于</h4>
                    <div class="ui inverted link list">
                        <a href="homepage.php#" class="item">Sitemap</a>
                        <a href="homepage.php#" class="item">Contact Us</a>
                        <a href="homepage.php#" class="item">Religious Ceremonies</a>
                        <a href="homepage.php#" class="item">Gazebo Plans</a>
                    </div>
                </div>
                <div class="three wide column">
                    <h4 class="ui inverted header">服务</h4>
                    <div class="ui inverted link list">
                        <a href="homepage.php#" class="item">Banana Pre-Order</a>
                        <a href="homepage.php#" class="item">DNA FAQ</a>
                        <a href="homepage.php#" class="item">How To Access</a>
                        <a href="homepage.php#" class="item">Favorite X-Men</a>
                    </div>
                </div>
                <div class="seven wide column">
                    <h4 class="ui inverted header">页脚</h4>
                    <p>Extra space for a call to action inside the footer that could help re-engage users.</p>
                </div>
            </div>
        </div>
    </div>
</secation>
<!--/footer-->
<?php $this->endBody() ?>
<script>
     $('.ui.dropdown').dropdown({action: 'combo'});
   // $('.ui.dropdown').dropdown({action: 'hide'});
</script>
</body>
</html>
<?php $this->endPage() ?>
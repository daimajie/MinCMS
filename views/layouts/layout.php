<?php
use yii\helpers\Html;
use app\assets\SemanticUIAsset;
use app\assets\RequireJsAsset;
use app\assets\HomeAsset;
use app\assets\Html5Asset;
use yii\helpers\Url;
use yii\widgets\Menu;


SemanticUIAsset::register($this);
RequireJsAsset::register($this);
HomeAsset::register($this);
Html5Asset::register($this);

$user = Yii::$app->user->identity;
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
                <?= Menu::widget([
                    'options' => ['tag'=>false],
                    'itemOptions' => ['tag'=>'a','class'=>'item teal menu-btn'],
                    'linkTemplate' => '<b data-url="{url}">{label}</b>',
                    'items' => [
                        ['label' => '首页', 'url' => ['index/index']],
                        ['label' => '分类', 'url' => ['category/index']],
                        ['label' => '话题', 'url' => ['topic/topics']],
                        ['label' => '随笔', 'url' => ['notes/index']],
                        ['label' => '关于我', 'url' => ['site/about']],
                    ],
                ]);
                ?>

                <div class="right menu">
                    <?php
                    if(!Yii::$app->user->isGuest):
                    ?>
                    <!--登录状态-->
                    <div class="ui dropdown item top right pointing">
                        <div class="text">
                            <img class="ui avatar image" src="<?= !empty($user['image']) ? IMG_ROOT . $user['image'] : Yii::$app->params['image']?>">
                            <?= $user['username']?>
                            <i class="dropdown icon"></i>
                        </div>
                        <div class="menu">
                            <a href="javascript:;" class="item">个人中心</a>
                            <a href="javascript:;" class="item">写作</a>
                            <a href="<?= Url::to(['index/logout'])?>" class="item">退出<i class="sign out icon"></i></a>
                        </div>
                    </div>
                    <?php
                    else:
                    ?>
                    <!--游客状态-->
                    <div class="item">
                        <div class="ui mini icon buttons">
                            <a href="<?= Url::to(['index/login'])?>" class="ui teal button">
                                <i class="sign in icon"></i>
                                登录
                            </a>
                            <a href="<?= Url::to(['index/register'])?>" class="ui brown button">
                                注册
                            </a>
                        </div>
                    </div>
                    <?php
                    endif;
                    ?>
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
     $('.menu-btn').on('click',function(){
         var url = $(this).find('b').data('url');
         window.location.href= url;
     });
</script>
</body>
</html>
<?php $this->endPage() ?>
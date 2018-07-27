<?php
use yii\helpers\Html;
use app\assets\SemanticUIAsset;
use app\assets\RequireJsAsset;
use app\assets\HomeAsset;
use app\assets\Html5Asset;
use yii\helpers\Url;
use yii\widgets\Menu;
use yii\widgets\ActiveForm;
use app\components\helper\Helper;

SemanticUIAsset::register($this);
RequireJsAsset::register($this);
HomeAsset::register($this);
Html5Asset::register($this);

$user = Yii::$app->user->identity;
$keyword = isset($this->params['keyword']) ? $this->params['keyword'] : null;
$about = Yii::$app->params['about'];
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
        <div class="ui container" >
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
                            <a href="<?= Url::to(['member/index'])?>" class="item">个人中心</a>
                            <?php if(Yii::$app->user->identity->group):?>
                            <a  href="<?= Url::to(['admin/default/frame'])?>" class="item">管理</a>
                            <?php endif;?>
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
            <?php
            $form = ActiveForm::begin([
                    'action' => ['search/index'],
            ]);
            ?>
                <div class="input-search">
                    <div class="icon-search"><img src="https://static3w.kuaikanmanhua.com/static/img/search_5e7251b.png"></div>
                    <?= Html::textInput('keyword',$keyword,[
                        'id' => 'txtKey',
                        'class' => 'txt',
                        'placeholder' => '搜索文章标题',
                        'autocomplete' => 'off',
                    ]);?>
                    <?= Html::submitButton('搜索',['class'=>'btn','id'=>'btnSearch']);?>
                </div>
            <?php ActiveForm::end();?>
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
                        <?php foreach($about['about'] as$key => $item):?>
                            <a href="<?= $item?>" class="item"><?= $key?></a>
                        <?php endforeach;?>
                    </div>
                </div>
                <div class="three wide column">
                    <h4 class="ui inverted header">服务</h4>
                    <div class="ui inverted link list">
                        <?php foreach($about['follow'] as$key => $item):?>
                            <a href="<?= $item?>" class="item"><?= $key?></a>
                        <?php endforeach;?>
                    </div>
                </div>
                <div class="seven wide column">
                    <h4 class="ui inverted header">关于</h4>
                    <p><?= Helper::truncate_utf8_string($about['info'],120)?></p>
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
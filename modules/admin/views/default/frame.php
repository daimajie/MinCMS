<?php
use yii\helpers\Url;

$menu = Yii::$app->params['menu'];
?>
<div id="hou-app">
    <div class="hou-layout hou-layout-admin">
        <div class="hou-header">
            <div class="hou-logo">
                <?= $name?>
            </div>
            <div class="hou-nav">
                <li>
                    <a href="javascript:;" title="隐藏边栏" class="_option_side"><i class="outdent icon"></i></a>
                </li>
                <li>
                    <a href="javascript:;" title="刷新" class="_reload_iframe"><i class="sync alternate icon"></i></a>
                </li>
            </div>
        </div>
        <div class="hou-side" id="hou-side">
            <div class="hou-user">
                <div class="hou-user-avatar">
                    <img src="<?= $user->image ? IMG_ROOT . $user->image : $defaultImage;?>" alt="">
                </div>
                <div class="hou-user-info">
                    <a href="" class="nickname">
                        <?= $user->username?>
                        <i class="caret down icon"></i>
                    </a>
                    <a href="<?= Url::to(['/index/logout'])?>" class="logout">退出</a>
                </div>
            </div>
            <div class="hou-menus">
                <div class="ui vertical secondary inverted accordion fluid menu">
                    <div class="item">
                        <a class="title"><i class="home icon"></i><i class="dropdown icon"></i>后台主页</a>
                        <div class="content">
                            <a class="item" target="contentFrame" href="<?= Url::to(['default/index'])?>">
                                <i class="ui setting icon"></i>
                                控制台
                            </a>
                        </div>
                    </div>
                    <?php
                    foreach($menu as $item):
                        $flag = 'hide';
                        if(Yii::$app->user->can($item['url'])){
                            $flag = 'show';
                        }else{
                            foreach ($item['children'] as $sub){
                                if(Yii::$app->user->can($sub['url'])){
                                    $flag = 'show';
                                    break;
                                }
                            }
                        }
                    ?>
                        <div class="item <?= $flag?>">
                            <a class="title"><i class="<?= $item['icon']?>"></i><i class="dropdown icon"></i><?= $item['label']?></a>
                            <div class="content">

                                <?php
                                foreach($item['children'] as $child):
                                    $flag = 'hide';
                                    if(Yii::$app->user->can($item['url']))
                                        $flag = 'show';
                                    elseif(Yii::$app->user->can($child['url']))
                                        $flag = 'show';
                                ?>
                                <a class="item <?= $flag?>" target="contentFrame" href="<?= Url::to([$child['url']])?>">
                                    <i class="<?= $child['icon']?>"></i>
                                    <?= $child['label']?>
                                </a>
                                <?php endforeach;?>
                            </div>
                        </div>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
        <div class="hou-page-nav">
            <div class="title-box">
                <div class="ui breadcrumb">
                    <a href="<?= Url::to([''])?>" class="section"><i class="ui icon setting"></i>控制台</a>
                </div>
            </div>

        </div>
        <div class="hou-body" id="hou-body">
            <iframe id="IFrame" name="contentFrame" src="<?= Url::to(['index'])?>" frameborder="0" class="hou-iframe"></iframe>
        </div>
    </div>
</div>
<div id="modals" class="hou-modals"></div>

<?php
$jsStr = <<<JS
    $('.ui.accordion').accordion();
    require(['mods/app','mods/dropdown'],function(app,dropdown){
        app.reloadIFrame();
        app.optionSide();

        dropdown.init('dropodown');
    });
JS;
$this->registerJs($jsStr);

$css = <<<CSS
    .hou-menus .item.hide{
        display: none!important;
    }
CSS;
$this->registerCss($css);
?>
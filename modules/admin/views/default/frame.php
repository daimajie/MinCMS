<?php
use yii\helpers\Url;

?>
<div id="hou-app">
    <div class="hou-layout hou-layout-admin">
        <div class="hou-header">
            <div class="hou-logo">
                DAIMAJIE.COM
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
                    <img src="/static/home/img/avatar.jpg" alt="">
                </div>
                <div class="hou-user-info">
                    <a href="" class="nickname">
                        阿北2017
                        <i class="caret down icon"></i>
                    </a>
                    <a href="" class="logout">退出</a>
                </div>
            </div>
            <div class="hou-menus">
                <div class="ui vertical secondary inverted accordion fluid menu">
                    <div class="item">
                        <a class="title"><i class="home icon"></i><i class="dropdown icon"></i>后台主页</a>
                        <div class="content">
                            <a class="item" target="contentFrame" href="pages/bootstrap.html">
                                <i class="ui setting icon"></i>
                                控制台
                            </a>
                        </div>
                    </div>
                    <!--菜单-->
                    <div class="item">
                        <a class=" title"><i class="align justify icon"></i><i class="dropdown icon"></i>内容管理</a>
                        <div class=" content">
                            <a class="item" target="contentFrame" href="<?= Url::to(['content/category/index'])?>">
                                <i class="flag icon"></i>
                                分类
                            </a>
                            <a class="item" target="contentFrame" href="<?= Url::to(['content/topic/index'])?>">
                                <i class="sticky note outline icon"></i>
                                话题
                            </a>
                            <a class="item" target="contentFrame" href="<?= Url::to(['content/tag/index'])?>">
                                <i class="ui tag icon"></i>
                                标签
                            </a>
                            <a class="item" target="contentFrame" href="<?= Url::to(['content/article/index'])?>">
                                <i class="send outline icon"></i>
                                文章
                            </a>
                        </div>
                    </div>
                    <!--/菜单-->
                    <div class="item">
                        <a class=" title"><i class="group icon"></i><i class="dropdown icon"></i>用户管理</a>
                        <div class=" content">
                            <a class="item" target="contentFrame" href="<?= Url::to(['member/user/index'])?>">
                                <i class="user icon"></i>
                                用户
                            </a>
                            <a class="item" target="contentFrame" href="<?= Url::to(['member/assign/index'])?>">
                                <i class="privacy icon"></i>
                                指派
                            </a>
                        </div>
                    </div>
                    <!--/菜单-->
                    <div class="item">
                        <a class=" title"><i class="unlock alternate icon"></i><i class="dropdown icon"></i>权限管理</a>
                        <div class=" content">
                            <a class="item" target="contentFrame" href="<?= Url::to(['rbac/rule/index'])?>">
                                <i class="filter icon"></i>
                                规则
                            </a>
                            <a class="item" target="contentFrame" href="<?= Url::to(['rbac/item/index'])?>">
                                <i class="compress icon"></i>
                                权限
                            </a>
                            <a class="item" target="contentFrame" href="<?= Url::to(['rbac/allot/index'])?>">
                                <i class="wizard icon"></i>
                                分配
                            </a>
                        </div>
                    </div>
                    <!--/菜单-->
                    <div class="item">
                        <a class=" title"><i class="settings icon"></i><i class="dropdown icon"></i>系统管理</a>
                        <div class=" content">
                            <a class="item" target="contentFrame" href="<?= Url::to(['setting/friend/index'])?>">
                                <i class="linkify icon"></i>
                                友链
                            </a>
                            <a class="item" target="contentFrame" href="<?= Url::to(['setting/metadata/setup'])?>">
                                <i class="puzzle icon"></i>
                                元数据
                            </a>
                        </div>
                    </div>
                    <!--/菜单-->
                    <div class="item">
                        <a class=" title"><i class="comments icon"></i><i class="dropdown icon"></i>评论管理</a>
                        <div class=" content">
                            <a class="item" target="contentFrame" href="<?= Url::to(['comment/comment/index']);?>">
                                <i class="linkify icon"></i>
                                评论
                            </a>
                        </div>
                    </div>
                    <!--/菜单-->
                    <div class="item">
                        <a class=" title"><i class="database icon"></i><i class="dropdown icon"></i>数据管理</a>
                        <div class=" content">
                            <a class="item" target="contentFrame" href="<?= Url::to(['data/log/index'])?>">
                                <i class="puzzle icon"></i>
                                日志
                            </a>
                            <a class="item" target="contentFrame" href="javascript:;">
                                <i class="linkify icon"></i>
                                备份
                            </a>
                            <a class="item" target="contentFrame" href="javascript:;">
                                <i class="options icon"></i>
                                缓存
                            </a>
                        </div>
                    </div>



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
?>
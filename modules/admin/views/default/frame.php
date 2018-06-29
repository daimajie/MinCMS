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
                    <img src="https://semantic-ui.com/images/avatar/large/veronika.jpg" alt="">
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
                                <i class="tachometer alternate icon"></i>
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
                            <a class="item" target="contentFrame" href="<?= Url::to(['content/article/index'])?>">
                                <i class="send outline icon"></i>
                                文章
                            </a>
                        </div>
                    </div>
                    <!--/菜单-->
                    <div class="item">
                        <a class=" title"><i class="th icon"></i><i class="dropdown icon"></i>页面布局</a>
                        <div class=" content">
                            <a class="item" target="contentFrame" href="https://nai8.me/hou/container/index.html">
                                <i class="th icon"></i>
                                容器
                            </a>
                            <a class="item" target="contentFrame" href="https://nai8.me/hou/container/grid.html">
                                <i class="chess board icon"></i>
                                网格
                            </a>
                        </div>
                    </div>

                    <div class="item">
                        <a class="title">
                            <i class="gift icon"></i><i class="dropdown icon"></i>功能组件

                        </a>
                        <div class=" content">
                            <a class="item" target="contentFrame" href="https://nai8.me/hou/component/button.html">
                                <i class="check icon"></i>
                                按钮
                            </a>
                            <a class="item" target="contentFrame" href="https://nai8.me/hou/component/menu.html">
                                <i class="list alternate outline icon"></i>
                                菜单
                            </a>
                            <a class="item" target="contentFrame" href="https://nai8.me/hou/component/dropdown.html">
                                <i class="tasks icon"></i>
                                下拉框
                            </a>
                            <a class="item" target="contentFrame" href="https://nai8.me/hou/component/icon.html">
                                <i class="image outline icon"></i>
                                图标
                            </a>
                            <a class="item" target="contentFrame" href="https://nai8.me/hou/component/popup.html">
                                <i class="envira icon"></i>
                                气泡提示
                            </a>
                            <a class="item" target="contentFrame" href="https://nai8.me/hou/component/modal.html">
                                <i class="clone outline icon"></i>
                                弹出框
                            </a>
                            <a class="item" target="contentFrame" href="https://nai8.me/hou/component/upload.html">
                                <i class="cloud upload icon"></i>
                                上传文件
                            </a>

                            <a class="item" target="contentFrame" href="https://nai8.me/hou/component/jsmart.html">
                                <i class="js square icon"></i>
                                js模板引擎
                            </a>

                            <a class="item" target="contentFrame" href="https://nai8.me/hou/component/swiper.html">
                                <i class="js book icon"></i>
                                轮播图
                            </a>

                            <a class="item" target="contentFrame" href="https://nai8.me/hou/component/video.html">
                                <i class="video icon"></i>
                                视频播放器
                            </a>

                            <a class="item" target="contentFrame" href="https://nai8.me/hou/component/simplemde.html">
                                <i class="edit outline icon"></i>
                                MarkDown编辑器
                            </a>
                        </div>
                    </div>

                    <div class="item">
                        <a class=" title"><i class="wpforms icon"></i><i class="dropdown icon"></i>表单组件</a>
                        <div class=" content">
                            <a class="item" target="contentFrame" href="https://nai8.me/hou/component/form-simple.html">
                                简单表单
                            </a>
                            <a class="item" target="contentFrame" href="https://nai8.me/hou/component/form-layout.html">
                                表单布局
                            </a>
                            <a class="item" target="contentFrame" href="https://nai8.me/hou/component/form-state.html">
                                表单状态
                            </a>
                        </div>
                    </div>

                    <div class="item">
                        <a class=" title"><i class="table icon"></i><i class="dropdown icon"></i>表格组件</a>
                        <div class=" content">
                            <a class="item" target="contentFrame" href="https://nai8.me/hou/component/table.html">
                                基本表格
                            </a>
                        </div>
                    </div>

                    <div class="item">
                        <a class=" title"><i class="newspaper icon"></i><i class="dropdown icon"></i>页面模板</a>
                        <div class=" content">
                            <a class="item" target="contentFrame" href="https://nai8.me/hou/default/bootstrap.html">
                                登录页面
                            </a>
                            <a class="item" target="contentFrame" href="https://nai8.me/hou/default/bootstrap.html">
                                404
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hou-page-nav">
            <div class="title-box">
                <div class="ui breadcrumb">
                    <a class="section">首页</a>
                    <i class="right chevron icon divider"></i>
                    <a class="section">分类管理</a>
                    <i class="right arrow icon divider"></i>
                    <div class="active section">新建分类</div>
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
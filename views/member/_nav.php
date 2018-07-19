<?php
use yii\helpers\Url;
use yii\helpers\Html;

?>

<div class="left-area four wide column" >
    <div class="ui vertical menu pointing ">
        <div class="item">
            <div class="header">完善信息</div>
            <div class="menu">
                <a href="<?= Url::to(['member/index'])?>" class="item active">个人资料</a>
                <a href="<?= Url::to(['member/set'])?>" class="item">资料设置</a>
                <a href="<?= Url::to(['member/writing'])?>" class="item">成为作者</a>
            </div>
        </div>
        <div class="item">
            <div class="header">账户设置</div>
            <div class="menu">
                <a href="<?= Url::to(['member/image'])?>" class="item">修改头像</a>
                <a href="<?= Url::to(['member/reset-passwd'])?>" class="item">修改密码</a>
                <a href="<?= Url::to(['member/reset-email'])?>" class="item">修改邮箱</a>
            </div>
        </div>
        <div class="item">
            <div class="header">内容管理</div>
            <div class="menu">
                <a  href="<?= Url::to(['member/likes'])?>" class="item">喜欢的文章</a>
                <a  href="<?= Url::to(['member/collect'])?>" class="item">收藏的文章</a>
            </div>
        </div>
    </div>
</div>
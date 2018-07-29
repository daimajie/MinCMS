<?php
use yii\helpers\Url;
use yii\widgets\Menu;


$this->title = '个人中心 - ' . $this->params['name'];
?>

<div class="left-area four wide column" >
    <div class="ui vertical menu pointing ">
        <div class="item">
            <div class="header">完善信息</div>
            <div class="menu">
                <?= Menu::widget([
                    'options' => ['tag'=>false],
                    'itemOptions' => ['tag'=>'span','class'=>'item'],
                    'linkTemplate' => '<a href="{url}">{label}</a>',
                    'items' => [
                        ['label' => '个人资料', 'url' => ['member/index']],
                        ['label' => '资料设置', 'url' => ['member/set']],
                        ['label' => '成为作者', 'url' => ['member/writing']],
                    ],
                ]);
                ?>
            </div>
        </div>
        <div class="item">
            <div class="header">账户设置</div>
            <div class="menu">
                <?= Menu::widget([
                    'options' => ['tag'=>false],
                    'itemOptions' => ['tag'=>'span','class'=>'item'],
                    'linkTemplate' => '<a href="{url}">{label}</a>',
                    'items' => [
                        ['label' => '修改头像', 'url' => ['member/image']],
                        ['label' => '修改密码', 'url' => ['member/reset-passwd']],
                        ['label' => '修改邮箱', 'url' => ['member/reset-email']],
                    ],
                ]);
                ?>
            </div>
        </div>
        <div class="item">
            <div class="header">内容管理</div>
            <div class="menu">
                <?= Menu::widget([
                    'options' => ['tag'=>false],
                    'itemOptions' => ['tag'=>'span','class'=>'item'],
                    'linkTemplate' => '<a href="{url}">{label}</a>',
                    'items' => [
                        ['label' => '喜欢的文章', 'url' => ['member/likes']],
                        ['label' => '收藏的文章', 'url' => ['member/collect']],
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
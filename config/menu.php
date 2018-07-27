<?php
//后台导航
return [
    //内容管理
    ['label' => '内容管理', 'url'=>'content/category/*', 'icon'=> 'align justify icon', 'children'=>[
        ['label'=>'分类', 'url' => 'content/category/index', 'icon' => 'flag icon'],
        ['label'=>'话题', 'url' => 'content/topic/index', 'icon' => 'ui setting icon'],
        ['label'=>'标签', 'url' => 'content/tag/index', 'icon' => 'ui tag icon'],
        ['label'=>'文章', 'url' => 'content/article/index', 'icon' => 'send outline icon'],
    ]],

    //成员管理
    ['label' => '用户管理', 'url'=>'member/user/*', 'icon'=> 'group icon', 'children'=>[
        ['label'=>'用户', 'url' => 'member/user/index', 'icon' => 'user icon'],
        ['label'=>'指派', 'url' => 'member/assign/index', 'icon' => 'privacy icon'],
    ]],

    //权限管理
    ['label' => '权限管理', 'url'=>'rbac/rule/*', 'icon'=> 'unlock alternate icon', 'children'=>[
        ['label'=>'规则', 'url' => 'rbac/rule/index', 'icon' => 'filter icon'],
        ['label'=>'权限', 'url' => 'rbac/item/index', 'icon' => 'compress icon'],
        ['label'=>'分配', 'url' => 'rbac/allot/index', 'icon' => 'wizard icon'],
    ]],

    //系统管理
    ['label' => '系统管理', 'url'=>'setting/friend/*', 'icon'=> 'settings icon', 'children'=>[
        ['label'=>'友链', 'url' => 'setting/friend/index', 'icon' => 'linkify icon'],
        ['label'=>'元数据', 'url' => 'setting/metadata/setup', 'icon' => 'puzzle icon'],
    ]],

    //评论管理
    ['label' => '评论管理', 'url'=>'comment/comment/*', 'icon'=> 'comment icon', 'children'=>[
        ['label'=>'评论', 'url' => 'comment/comment/index', 'icon' => 'linkify icon'],
    ]],

    //数据管理
    ['label' => '数据管理', 'url'=>'data/log/*', 'icon'=> 'database icon', 'children'=>[
        ['label'=>'日志', 'url' => 'data/log/index', 'icon' => 'puzzle icon'],
        ['label'=>'备份', 'url' => '#', 'icon' => 'linkify icon'],
        ['label'=>'缓存', 'url' => '#', 'icon' => 'options icon'],
    ]],
];
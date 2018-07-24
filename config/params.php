<?php

return [
    //重置密码token过期时间
    'user.passwordResetTokenExpire' => 1800,

    //管理员邮箱
    'adminEmail' => 'git1314@163.com',

    //图片上传配置
    'imgPath' => [
        'imgUrl' => '/uploaded',       //图片显示url
        'imgUp'  => realpath(getcwd() . '/uploaded'),   //图片上传路径
        'allowPath'  => ['tempPath','category', 'topic', 'article'],   //允许创建的图片分类目录
    ],

    //域名
    'domain' => 'http://www.dev.com/',

    //一个话题下允许有多少个标签
    'tagUpperLimit' => 35,

    //默认头像
    'image' => '/static/home/img/avatar.jpg',

    //关于我
    'about' => include 'about.php',


];

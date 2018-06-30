<?php

return [
    'adminEmail' => 'daimajie@qq.com',

    //图片上传配置
    'imgPath' => [
        'imgUrl' => '/uploaded',       //图片显示url
        'imgUp'  => realpath(getcwd() . '/uploaded'),   //图片上传路径
        'allowPath'  => ['tempPath','category', 'topic', 'article'],   //允许创建的图片分类目录
    ],

    //一个话题下允许有多少个标签
    'tagUpperLimit' => 35,


];

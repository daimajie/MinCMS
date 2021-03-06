<?php

return [
    'layout' => 'frame',
    'modules' => [
        'content' => [
            'class' => 'app\modules\admin\modules\content\Module',
        ],
        'member' => [
            'class' => 'app\modules\admin\modules\member\Module',
        ],
        'rbac' => [
            'class' => 'app\modules\admin\modules\rbac\Module',
        ],
        'setting' => [
            'class' => 'app\modules\admin\modules\setting\Module',
        ],
        'comment' => [
            'class' => 'app\modules\admin\modules\comment\Module',
        ],
        'data' => [
            'class' => 'app\modules\admin\modules\data\Module',
        ],
    ],
    'components' => [

    ],
    'as access' => [
        'class' => 'app\modules\admin\components\AccessControl',
    ],

];

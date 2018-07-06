<?php

return [
    'layout' => 'main',
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
    ],
    'components' => [

    ],
    'params' => [
        // list of parameters
    ],
];

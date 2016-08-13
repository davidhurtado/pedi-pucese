<?php

return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
    'modules' => [
        'user' => [
            'class' => 'dektrium\user\Module',
            'enableUnconfirmedLogin' => true,
            'confirmWithin' => 21600,
            'cost' => 12,
            'enableRegistration' => true,
        ],
        'rbac' => [
            'class' => 'dektrium\rbac\Module',
        ],
        'gridview' => [
            'class' => '\kartik\grid\Module'
        ],
        [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@kvgrid/messages',
            'forceTranslation' => true
        ]
    ],
];

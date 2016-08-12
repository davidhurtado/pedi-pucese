<?php

use yii\helpers\Url;

return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'id',
    // ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'descripcion',
    ],
    /* [
      'class'=>'\kartik\grid\DataColumn',
      'attribute'=>'responsables',
      ], */
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'fecha_inicio',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'fecha_fin',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'urlCreator' => function($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
                'viewOptions' => ['title' => 'Ver',],
                'updateOptions' => ['role' => 'modal-remote', 'title' => 'Actualizar', 'data-toggle' => 'tooltip'],
                'deleteOptions' => ['role' => 'modal-remote', 'title' => 'Eliminar',
                    'data-confirm' => false, 'data-method' => false, // for overide yii data api
                    'data-request-method' => 'post',
                    'data-toggle' => 'tooltip',
                    'data-confirm-title' => 'Est&aacute;s Seguro?',
                    'data-confirm-message' => 'Est&aacute;s seguro de eliminar esto?'],
            ],
        ];
        
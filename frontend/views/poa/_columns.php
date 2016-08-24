<?php

use yii\helpers\Url;
use yii\helpers\Html;

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
        'attribute' => 'fecha_creacion',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'fecha_ejecucion',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'fecha_fin',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'estado',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'urlCreator' => function($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key,'estado' => 'ver']);
        },
                'template' => '{view}{projects}{update}{delete}',
                'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
                'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
                'deleteOptions' => ['role' => 'modal-remote', 'title' => 'Delete',
                    'data-confirm' => false, 'data-method' => false, // for overide yii data api
                    'data-request-method' => 'post',
                    'data-toggle' => 'tooltip',
                    'data-confirm-title' => 'Are you sure?',
                    'data-confirm-message' => 'Are you sure want to delete this item'],
                'buttons' => [
                    'projects' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-folder-open"></span>', ['poa/validar','id'=>$model->id], [
                                    'title' => 'Proyectos',
                                    'data-toggle' => 'tooltip'
                                        ]
                        );
                    },
                        ],
                    ],
                ];
                
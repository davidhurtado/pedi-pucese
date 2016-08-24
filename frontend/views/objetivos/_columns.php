<?php

use yii\helpers\Url;

return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    /* [
      'class' => 'kartik\grid\SerialColumn',
      'width' => '30px',
      ], */
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'numeracion',
        'label' => 'Nro',
        'contentOptions' => ['style' => 'width: 10px; text-align:center'],
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'descripcion',
    ],
    /* [
      'class'=>'\kartik\grid\DataColumn',
      'attribute'=>'colaboradores',
      ], */
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'fecha_inicio',
        'contentOptions' => ['style' => 'width: 85px; text-align:center'],
        'label'=>'Año Inicial',
        'value'=> function ($data){
        return substr($data->fecha_inicio,0,strpos($data->fecha_inicio, "-",1));
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'fecha_fin',
        'contentOptions' => ['style' => 'width: 80px; text-align:center'],
        'label'=>'Año Final',
        'value'=> function ($data){
        return substr($data->fecha_fin,0,strpos($data->fecha_inicio, "-",1));
        }
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
        
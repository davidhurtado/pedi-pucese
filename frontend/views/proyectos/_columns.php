<?php

use yii\helpers\Url;
use app\models\Proyectos;
use app\models\Programas;
use yii\helpers\Html;
return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    /*[
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],*/
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'id',
    // ],
    /* [
      'class' => '\kartik\grid\DataColumn',
      'attribute' => 'id_programa',
      ], */
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'numeracion',
        'label' => 'Programa',
        //'filter' => false,
        'contentOptions' => ['style' => 'width: 10px; text-align:center'],
        'value' => function($data) {
    $programa = Programas::findOne($data->id_programa);
    return Html::tag('strong', $programa->numeracion, ['data-toggle' => 'tooltip', 'title' => '[PROGRAMA] ' . $programa->descripcion, 'style' => 'cursor:default;']);
},
        'format' => 'raw',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nombre',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'descripcion',
    ],
    /* [
      'class' => '\kartik\grid\DataColumn',
      'attribute' => 'responsables',
      ], */
    /* [
      'class' => '\kartik\grid\DataColumn',
      'attribute' => 'fecha_inicio',
      ], */
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'fecha_fin',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'presupuesto',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'urlCreator' => function($action, $model, $key, $index) {
            $modelo = str_replace("app\\models\\", "", get_class($model));
            return Url::to([strtolower($modelo) . '/' . $action, 'id' => $key]);
        },
                'viewOptions' => ['title' => 'View'],
                'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
                'deleteOptions' => ['role' => 'modal-remote', 'title' => 'Delete',
                    'data-confirm' => false, 'data-method' => false, // for overide yii data api
                    'data-request-method' => 'post',
                    'data-toggle' => 'tooltip',
                    'data-confirm-title' => 'Est&aacute;s Seguro?',
                    'data-confirm-message' => 'Est&aacute;s seguro de eliminar esto?'],
            ],
        ];
        
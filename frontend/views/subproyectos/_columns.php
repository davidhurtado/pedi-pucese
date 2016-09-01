<?php

use yii\helpers\Url;

return [
    /* [
      'class' => 'kartik\grid\CheckboxColumn',
      'width' => '20px',
      ], */
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'id',
    // ],
    /* [
      'class' => '\kartik\grid\DataColumn',
      'attribute' => 'id_proyecto',
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
        'class' => 'kartik\grid\DataColumn',
        'attribute' => 'estado',
        'contentOptions' => ['style' => 'width: 10px;'],
        'value' => function($data) {
    $estado = '';
    switch ($data['estado']) {
        case 1:
            $estado = 'borrador';
            break;
        case 2:
            $estado = 'ok';
            break;
        case 3:
            $estado = 'ejecucion';
            break;
        case 4:
            $estado = 'terminado';
            break;
    }
    return $estado;
},
    ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'fecha_fin',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{view}',
        'vAlign' => 'middle',
        'urlCreator' => function($action, $model, $key, $index) {
            $modelo = str_replace("app\\models\\", "", get_class($model));
            return Url::to([strtolower($modelo) . '/' . $action, 'id' => $key]);
        },
                'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
            ],
        ];
        
<?php

use yii\helpers\Url;
use app\models\Proyectos;
use app\models\Estrategias;
use app\models\Objetivos;
use app\models\Programas;
use yii\helpers\Html;
use kartik\grid\GridView;

return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    /* [
      'class' => 'kartik\grid\SerialColumn',
      'width' => '30px',
      ], */
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'id',
    // ],
    [
        'class' => '\kartik\grid\DataColumn',
        'label' => 'Objetivo',
        'filter' => false,
        'contentOptions' => ['style' => 'width: 10px; text-align:center'],
        'value' => function($data) {
    $programa = Programas::findOne($data->id_programa);
    $estrategia = Estrategias::findOne($programa->id_estrategia);
    $objetivo = Objetivos::findOne($estrategia->id_objetivo);
    return Html::tag('strong', $objetivo->numeracion, ['data-toggle' => 'tooltip', 'title' => '[OBJETIVO] ' . $objetivo->descripcion, 'style' => 'cursor:default;']);
},
        'format' => 'raw',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'label' => 'Estrategia',
        'filter' => false,
        'contentOptions' => ['style' => 'width: 10px; text-align:center'],
        'value' => function($data) {
    $programa = Programas::findOne($data->id_programa);
    $estrategia = Estrategias::findOne($programa->id_estrategia);
    return Html::tag('strong', $estrategia->numeracion, ['data-toggle' => 'tooltip', 'title' => '[ESTRATEGIA] ' . $estrategia->descripcion, 'style' => 'cursor:default;']);
},
        'format' => 'raw',
    ],
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
        'contentOptions' => ['style' => 'width: 10px;'],
        'value' => function($data) {
    return $data->numeracion . ': ' . Html::tag('span', substr(strip_tags($data->nombre), 0, 71) . '....', ['data-toggle' => 'tooltip', 'title' => $data->nombre, 'style' => 'cursor:default;']);
},
        'format' => 'raw',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'descripcion',
    ],
    /* [
      'class' => '\kartik\grid\DataColumn',
      'attribute' => 'colaboradores',
      ], */
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'fecha_inicio',
        'contentOptions' => ['style' => 'width: 85px; text-align:center'],
        'label' => 'Año Inicial',
        'value' => function ($data) {
    return substr($data->fecha_inicio, 0, strpos($data->fecha_inicio, "-", 1));
}
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'fecha_fin',
        'contentOptions' => ['style' => 'width: 80px; text-align:center'],
        'label' => 'Año Final',
        'value' => function ($data) {
    return substr($data->fecha_fin, 0, strpos($data->fecha_inicio, "-", 1));
}
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'validacion',
        'contentOptions' => ['style' => 'width: 10px; text-align:center'],
        'value' => function($data) {
    return $data['validacion'] == 1 ? 'Activado' : 'Desactivado';
},
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ['0' => 'Desactivado', '1' => 'Activado',],
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccione'],
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
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ['1' => 'Borrador', '2' => 'Ok', '3' => 'Ejecucion','4' => 'Terminado'],
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccione'],
    ],
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
                'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update',],
                'deleteOptions' => ['role' => 'modal-remote', 'title' => 'Delete',
                    'data-confirm' => false, 'data-method' => false, // for overide yii data api
                    'data-request-method' => 'post',
                    'data-toggle' => 'tooltip',
                    'data-confirm-title' => 'Est&aacute;s Seguro?',
                    'data-confirm-message' => 'Est&aacute;s seguro de eliminar esto?'],
            ],
        ];
        
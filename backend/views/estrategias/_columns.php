<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Objetivos;
use app\models\Estrategias;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

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
        'attribute' => 'id_objetivo',
        'label' => 'Objetivo',
        //'filter' => false,
        'contentOptions' => ['style' => 'width: 10px; text-align:center'],
        'value' => function($data) {
    $objetivo = Objetivos::findOne($data->id_objetivo);
    return Html::tag('strong', $objetivo->numeracion, ['data-toggle' => 'tooltip', 'title' => '[OBJETIVO] ' . $objetivo->descripcion, 'style' => 'cursor:default;']);
},
        'format' => 'raw',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        //'attribute'=>'descripcion',
        'attribute' => 'descripcion',
        'contentOptions' => ['style' => 'width: 10px;'],
        'value' => function($data) {
    return $data->numeracion . ': ' . Html::tag('span', $data->descripcion, ['data-toggle' => 'tooltip', 'title' => $data->descripcion, 'style' => 'cursor:default;']);
},
        'format' => 'raw',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Estrategias::find()->orderBy('id')->asArray()->all(), 'descripcion', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccionar Estrategias'],
    ],
    /* [
      'class'=>'\kartik\grid\DataColumn',
      'attribute'=>'colaboradores',
      ], */
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'fecha_inicio',
        'contentOptions' => ['style' => 'width: 10px; text-align:center'],
        'label' => 'Año Inicio',
        'value' => function ($data) {
    return substr($data->fecha_inicio, 0, strpos($data->fecha_inicio, "-", 1));
}
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'fecha_fin',
        'contentOptions' => ['style' => 'width: 10px; text-align:center'],
        'label' => 'Año Fin',
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
        'filterInputOptions' => ['placeholder' => 'Seleccionar Estado'],
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'urlCreator' => function($action, $model, $key, $index) {
            $modelo = str_replace("app\\models\\", "", get_class($model));
            return Url::to([strtolower($modelo) . '/' . $action, 'id' => $key,]);
        },
                'viewOptions' => ['title' => 'View',],
                'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
                'deleteOptions' => ['role' => 'modal-remote', 'title' => 'Delete',
                    'data-confirm' => false, 'data-method' => false, // for overide yii data api
                    'data-request-method' => 'post',
                    'data-toggle' => 'tooltip',
                    'data-confirm-title' => 'Est&aacute;s Seguro?',
                    'data-confirm-message' => 'Est&aacute;s seguro de eliminar esto?'],
            ],
        ];
        
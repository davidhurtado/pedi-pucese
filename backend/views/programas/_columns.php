<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Objetivos;
use app\models\Estrategias;
use app\models\Programas;
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
    $estrategia = Estrategias::findOne($data->id_estrategia);
    $objetivo = Objetivos::findOne($estrategia->id_objetivo);
    return Html::tag('strong', $objetivo->numeracion, ['data-toggle' => 'tooltip', 'title' => '[OBJETIVO] ' . $objetivo->descripcion, 'style' => 'cursor:default;']);
},
        'format' => 'raw',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'id_estrategia',
        'label' => 'Estrategia',
        //'filter' => false,
        'contentOptions' => ['style' => 'width: 10px; text-align:center'],
        'value' => function($data) {
    $estrategia = Estrategias::findOne($data->id_estrategia);
    return Html::tag('strong', $estrategia->numeracion, ['data-toggle' => 'tooltip', 'title' => '[ESTRATEGIA] ' . $estrategia->descripcion, 'style' => 'cursor:default;']);
},
        'format' => 'raw',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'descripcion',
         'contentOptions' => ['style' => 'width: 10px;'],
        'value' => function($data) {
            if (isset($data->id)) {
                $estrategia = Estrategias::findOne($data->id_estrategia);
                $objetivo = Objetivos::findOne($estrategia->id_objetivo);
                return $data->numeracion . ': ' . Html::tag('span', substr(strip_tags($data->descripcion), 0, 90) . '....', ['data-toggle' => 'tooltip', 'title' => $data->descripcion, 'style' => 'cursor:default;']);
            } else {
                return '';
            }
        },
                'format' => 'raw',
                'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map(Programas::find()->orderBy('id')->asArray()->all(), 'descripcion', 'descripcion'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Seleccionar Programa'],
            ],
            /* [
              'class' => '\kartik\grid\DataColumn',
              'attribute' => 'colaboradores',
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
                
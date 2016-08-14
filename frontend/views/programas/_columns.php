<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Objetivos;
use app\models\Estrategias;
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
      'attribute' => 'id_estrategia',
      ], */
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'descripcion',
        'value' => function($data) {
            if (isset($data->id)) {
                $estrategia = Estrategias::findOne($data->id_estrategia);
                $objetivo = Objetivos::findOne($estrategia->id_objetivo);
                return Html::tag('strong', $objetivo->getNumero($estrategia['id_objetivo']), ['data-toggle' => 'tooltip', 'title' => '[Objetivo] '.$objetivo->descripcion, 'style' => 'cursor:default;']) . '.'
                        .Html::tag('strong', $estrategia->getNumero($data->id_estrategia)['numeracion'], ['data-toggle' => 'tooltip', 'title' => '[Estrategia] '.$estrategia->descripcion, 'style' => 'cursor:default;']) . '.'
                        .$data->getNumero($data->id)['numeracion'] . ': ' . Html::tag('span', substr(strip_tags($data->descripcion), 0, 90) . '....', ['data-toggle' => 'tooltip', 'title' => $data->descripcion, 'style' => 'cursor:default;']);
            } else {
                return '';
            }
        },
                'format' => 'raw',
            ],
            /* [
              'class' => '\kartik\grid\DataColumn',
              'attribute' => 'responsables',
              ], */
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'fecha_inicio',
            ],
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'fecha_fin',
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
                
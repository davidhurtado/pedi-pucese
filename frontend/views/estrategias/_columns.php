<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Objetivos;
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
    /* [
      'class'=>'\kartik\grid\DataColumn',
      'attribute'=>'id_objetivo',
      ], */
    [
        'class' => '\kartik\grid\DataColumn',
        //'attribute'=>'descripcion',
        'attribute' => 'descripcion',
        'value' => function($data) {
            if (isset($data->id)) {
                $objetivo=Objetivos::findOne($data->id_objetivo);
                return Html::tag('strong','[Objetivo] ', ['data-toggle' => 'tooltip', 'title' => $objetivo->descripcion, 'style' => 'cursor:default;'])
                       .Html::tag('span', substr(strip_tags($data->descripcion),0,90).'....', ['data-toggle' => 'tooltip', 'title' => $data->descripcion, 'style' => 'cursor:default;']);
            } else {
                return '';
            }
        },
                'format' => 'raw',
            ],
            /*[
              'class'=>'\kartik\grid\DataColumn',
              'attribute'=>'responsables',
              ], */
             [
              'class'=>'\kartik\grid\DataColumn',
              'attribute'=>'fecha_inicio',
              ],
              [
              'class'=>'\kartik\grid\DataColumn',
              'attribute'=>'fecha_fin',
              ], 
             [
             'class'=>'\kartik\grid\DataColumn',
             'attribute'=>'presupuesto',
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
                
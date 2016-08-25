<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Objetivos;
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
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'numeracion',
        'label' => 'Nro',
        'contentOptions' => ['style' => 'width: 10px; text-align:center'],
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'descripcion',
        'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map(Objetivos::find()->orderBy('id')->asArray()->all(), 'descripcion', 'descripcion'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Seleccionar Objetivo'],
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
                'updateOptions' => ['role' => 'modal-remote', 'title' => 'Actualizar',],
                'deleteOptions' => ['role' => 'modal-remote', 'title' => 'Eliminar',
                    'data-confirm' => true, 'data-method' => false, // for overide yii data api
                    'data-request-method' => 'post',
                    'data-confirm-title' => 'Est&aacute;s Seguro?',
                    'data-confirm-message' => 'Est&aacute;s seguro de eliminar esto?'],
                
            ],
        ];
        
<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;

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
    /* [
      'class' => '\kartik\grid\DataColumn',
      'attribute' => 'estado',
      'contentOptions' => ['style' => 'width: 10px; text-align:center'],
      //'icon' => 'glyphicon glyphicon-expand',
      'value' => function($model, $key, $index, $column) {
      switch ($model->estado) {
      case 0:
      return Html::tag('span', ' Borrador', ['class' => 'label label-default glyphicon glyphicon-time ']);
      case 1:
      return Html::tag('span', ' Ejecución', ['class' => 'label label-warning glyphicon glyphicon-expand ']);
      case 2:
      default:
      return Html::tag('span', ' Terminado', ['class' => 'label label-success glyphicon glyphicon-ok ']);
      }
      },
      'format' => 'raw',
      'filterType' => GridView::FILTER_SELECT2,
      'filter' => ['0' => 'Borrador', '1' => 'Ejecucion','2' => 'Terminado',],
      'filterWidgetOptions' => [
      'pluginOptions' => ['allowClear' => true],
      ],
      'filterInputOptions' => ['placeholder' => 'Seleccionar Estado'],
      ], */
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'urlCreator' => function($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key, 'estado' => 'ver']);
        },
                'template' => '{view}&nbsp;&nbsp;{projects}&nbsp;&nbsp;{update}&nbsp;&nbsp;{delete}',
                'viewOptions' => ['role' => 'modal-remote', 'title' => 'View'],
                'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update'],
                'deleteOptions' => ['role' => 'modal-remote', 'title' => 'Delete',
                    'data-confirm' => false, 'data-method' => false, // for overide yii data api
                    'data-request-method' => 'post',
                    'data-confirm-title' => 'Are you sure?',
                    'data-confirm-message' => 'Are you sure want to delete this item'],
                'buttons' => [
                    'projects' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-folder-open"></span>', ['poa/proyectos', 'id' => $model->id,'accion'=>Yii::$app->controller->action->id], [
                                    'title' => 'Proyectos',
                                        ]
                        );
                    },
                        ],
                    ],
                ];
                
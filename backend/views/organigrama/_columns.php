<?php

use yii\helpers\Url;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
$query = new \yii\db\Query();
        $query->select('*')
                ->from('organigrama');

        $cmd = $query->createCommand()->queryAll();
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
        'attribute' => 'name',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' =>ArrayHelper::map($cmd, 'name', 'name'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccionar'],
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'created',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'activo',
        'contentOptions' => ['style' => 'width: 10px; text-align:center'],
        'value' => function($data) {
            return $data['activo'] == 1 ? 'Activado' : 'Desactivado';
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' =>['0' => 'Desactivado', '1' => 'Activado',],
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
            return Url::to([$action, 'id' => $key]);
        },
                'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
                'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
                'deleteOptions' => ['role' => 'modal-remote', 'title' => 'Delete',
                    'data-confirm' => false, 'data-method' => false, // for overide yii data api
                    'data-request-method' => 'post',
                    'data-toggle' => 'tooltip',
                    'data-confirm-title' => 'Are you sure?',
                    'data-confirm-message' => 'Are you sure want to delete this item'],
            ],
        ];
        
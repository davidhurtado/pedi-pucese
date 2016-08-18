<?php

use yii\helpers\Url;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use \yii\db\Query;

$query = new Query();
$query->select('*')->from('niveles');
$cmd = $query->createCommand()->queryAll();
$query_org = new Query();
$query_org->select('*')->from('organigrama');
$organigrama = $query_org->createCommand()->queryAll();
return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    /* [
      'class' => '\kartik\grid\DataColumn',
      'attribute' => 'nid',
      ], */
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'title',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map($cmd, 'title', 'title'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccionar Estado'],
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'rid',
        'value' => function($data) {
            $query = new Query();
            $query->select('*')->from('niveles')->where(['nid' => $data['rid']]);
            $cmd = $query->createCommand()->queryOne();
            return $cmd['title'];
        },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map($cmd, 'nid', 'title'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Seleccionar'],
            ],
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'org_id',
                'contentOptions' => ['style' => 'width: 10px; text-align:center'],
                'value' => function($data) {
            $query = new Query();
            $query->select('*')->from('organigrama')->where(['id' => $data['org_id']]);
            $cmd = $query->createCommand()->queryOne();
            return $cmd['name'];
        },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map($organigrama, 'id', 'name'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Seleccionar'],
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
                
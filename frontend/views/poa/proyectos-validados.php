<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use app\models\Proyectos;
use app\models\Estrategias;
use app\models\Objetivos;
use app\models\Programas;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProyectosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Aprobar Proyectos';
$this->params['breadcrumbs'][] = $this->title;
//var_dump($dataProvider);
//die();

$estado_poa = frontend\models\Poa::findOne(['id' => $_GET['id']])->estado == 0 ? false : true;
$template = '{view}';
$boton_aprobar='';
if ($estado_poa == false) {
    $boton_aprobar = Html::a('<i class="glyphicon glyphicon-check"></i> APROBAR', ['antes-de-aprobar', 'id' => $_GET['id'], 'estado' => 'ejecutar'], ['role' => 'modal-remote', 'title' => 'Aprobar POA', 'class' => 'btn btn-success']);
    $template = '{view}&nbsp;&nbsp;{update}';
}
CrudAsset::register($this);
?>
<div class="proyectos-index">
    <div id="ajaxCrudDatatable">
        <?=
        GridView::widget([
            'id' => 'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax' => true,
            'columns' => [
                [
                    'class' => '\kartik\grid\DataColumn',
                    'label' => 'Objetivo',
                    'filter' => false,
                    'contentOptions' => ['style' => 'width: 10px; text-align:center'],
                    'value' => function($data) {
                $programa = Programas::findOne($data['id_programa']);
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
                $programa = Programas::findOne($data['id_programa']);
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
                $programa = Programas::findOne($data['id_programa']);
                return Html::tag('strong', $programa->numeracion, ['data-toggle' => 'tooltip', 'title' => '[PROGRAMA] ' . $programa->descripcion, 'style' => 'cursor:default;']);
            },
                    'format' => 'raw',
                ],
                [
                    'class' => '\kartik\grid\DataColumn',
                    'attribute' => 'nombre',
                    'value' => function($data) {
                        return $data['numeracion'] . ': ' . Html::tag('span', substr(strip_tags($data['nombre']), 0, 110) . '....', ['data-toggle' => 'tooltip', 'title' => $data['nombre'], 'style' => 'cursor:default;']);
                    },
                            'format' => 'raw',
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
                        [
                            'class' => 'kartik\grid\ActionColumn',
                            'dropdown' => false,
                            'vAlign' => 'middle',
                            'urlCreator' => function($action, $model, $key, $index) {
                                return Url::to(['/' . $action, 'id' => $key,]);
                            },
                                    'template'=>$template,
                                    'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
                                    'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
          
                                    'buttons' => [
                                        'projects' => function ($url, $model) {
                                            return Html::a('<span class="glyphicon glyphicon-folder-open"></span>', ['poa/validar', 'id' => $model['id']], [
                                                        'title' => 'Proyectos',
                                                        'data-toggle' => 'tooltip'
                                                            ]
                                            );
                                        },
                                                'view' => function ($url, $model) {
                                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['poa/view-proyecto', 'id' => $model['id']], [
                                                        'title' => 'Proyectos',
                                                        'data-toggle' => 'tooltip',
                                                        'role' => 'modal-remote',
                                                            ]
                                            );
                                        },
                                                'update' => function ($url, $model) {
                                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['proyectos/update', 'id' => $model['id']], [
                                                        'title' => 'Actualizar',
                                                        'data-toggle' => 'tooltip',
                                                        'role' => 'modal-remote',
                                                            ]
                                            );
                                        },
                                            ],
                                        ],
                                    ],
                                    'toolbar' => [
                                        ['content' =>
                                            $boton_aprobar .
                                            Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['', 'id' => $_GET['id']], ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Reset Grid']) .
                                            //'{toggleData}' .
                                            '{export}'
                                        ],
                                    ],
                                    'striped' => true,
                                    'condensed' => true,
                                    'responsive' => true,
                                    'panel' => [
                                        'type' => 'primary',
                                        'heading' => '<i class="glyphicon glyphicon-list"></i> REVISIÓN DE PROYECTOS',
                                        'before' => '<h4>PROYECTOS A EJECUTAR</h4>',
                                        //'before'=>'<em>* Resize table columns just like a spreadsheet by dragging the column edges.</em>',
                                        'after' => BulkButtonWidget::widget([
                                            'buttons' => Html::a('<i class="glyphicon glyphicon-trash"></i>&nbsp; Eliminar todo', ["bulk-delete"], [
                                                "class" => "btn btn-danger btn-xs",
                                                'role' => 'modal-remote-bulk',
                                                'data-confirm' => false, 'data-method' => false, // for overide yii data api
                                                'data-request-method' => 'post',
                                                'data-confirm-title' => 'Est&aacute;s Seguro?',
                                                'data-confirm-message' => 'Est&aacute;s seguro de eliminar esto?'
                                            ]),
                                        ]) .
                                        '<div class="clearfix"></div>',
                                    ]
                                ])
                                ?>
                            </div>
                        </div>
                        <?php
                        Modal::begin([
                            'size' => Modal::SIZE_LARGE,
                            "id" => "ajaxCrudModal",
                            "footer" => "", // always need it for jquery plugin
                            'options' => [
                                'tabindex' => false // important for Select2 to work properly
                            ],
                        ])
                        ?>
                        <?php Modal::end(); ?>
                        <?php
                        $this->registerJs('$(\'.modal-lg\').css(\'width\', \'60%\');'
                                . '$(function () { $("[data-toggle=\'tooltip\']").tooltip(); });'
                                . '$(function () { $("[data-toggle=\'popover\']").popover();});');
                        ?>

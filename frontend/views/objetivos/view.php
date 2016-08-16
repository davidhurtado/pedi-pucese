<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use app\models\Programas;
use app\models\Estrategias;
use app\models\Objetivos;
use \yii\db\Query;

/* @var $this yii\web\View */
/* @var $model app\models\Objetivos */

$this->title = 'OBJETIVO ' . $model->numeracion;
$this->params['breadcrumbs'][] = ['label' => 'Objetivos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
Yii::$app->params['titulo_exportacion'] = $this->title . ': ' . $model->descripcion;
CrudAsset::register($this);
?>
<div class="objetivos-view">

    <h3><?= $model->descripcion ?></h3>

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id,], ['class' => 'btn btn-primary', 'role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip']) ?>
        <?=
        Html::a('Eliminar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'role' => 'modal-remote', 'title' => 'Delete',
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Are you sure?',
            'data-confirm-message' => 'Are you sure want to delete this item'])
        ?>
    </p>
    <div class="col-sm-12">
        <div class="row">
            <?=
            DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'fecha_inicio',
                    'fecha_fin',
                    [
                        'attribute' => 'responsables',
                        'value' => $model->getResponsables(array_map('intval', explode(',', $model->responsables))),
                    ],
                ],
            ])
            ?>

        </div>
    </div>

    <h3>ESTRATEGIAS</h3>
    <div class="estrategias-index">
        <div id="ajaxCrudDatatable">
            <?=
            GridView::widget([
                'id' => 'crud-datatable',
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'pjax' => true,
                'summary' => '',
                'columns' => [
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
                        'attribute' => 'descripcion',
                        'value' => function ($model, $key, $index, $widget) {
//                                $query = new Query();
//                                $query2 = new Query();
//                                $query->select('*')->from('numeracion_objetivo')->where(['id_objetivo' => $model->id_objetivo]);
//                                $numeracionObjetivo = $query->createCommand()->queryOne();
//                                
//                                $query2->select('*')->from('numeracion_estrategias')->where(['id_estrategia' => $model->id]);
//                                $numeracionEstrategia = $query2->createCommand()->queryOne();
                            $objetivo = Objetivos::findOne($model->id_objetivo);
                            return $objetivo->numeracion . '.' . $model->numeracion . ': ' . $model->descripcion;
                        },
                    ],
                    [
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'fecha_inicio',
                        'contentOptions' => ['style' => 'width: 10px; text-align:center'],
                        'label' => 'Año Inicial',
                        'value' => function ($data) {
                    return substr($data->fecha_inicio, 0, strpos($data->fecha_inicio, "-", 1));
                }
                    ],
                    [
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'fecha_fin',
                        'contentOptions' => ['style' => 'width: 10px; text-align:center'],
                        'label' => 'Año Final',
                        'value' => function ($data) {
                    return substr($data->fecha_fin, 0, strpos($data->fecha_inicio, "-", 1));
                }
                    ],
                    [
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'presupuesto',
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
                        ],
                        'toolbar' => [
                            ['content' =>
                                Html::a('<i class="glyphicon glyphicon-plus"></i>', ['estrategias/create', 'id' => $_GET['id']], ['role' => 'modal-remote', 'title' => 'Create new Estrategias', 'class' => 'btn btn-default']) .
                                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['', 'id' => $_GET['id']], ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Reset Grid']) .
                                '{toggleData}' .
                                '{export}'
                            ],
                        ],
                        'striped' => true,
                        'condensed' => true,
                        'responsive' => true,
                        'panel' => [
                            'type' => 'primary',
                            'heading' => '<i class="glyphicon glyphicon-list"></i> Estrategias ',
                            //'before' => '<em>* Resize table columns just like a spreadsheet by dragging the column edges.</em>',
                            'after' => BulkButtonWidget::widget([
                                'buttons' => Html::a('<i class="glyphicon glyphicon-trash"></i>&nbsp; Eliminar todo', ["estrategias/bulk-delete"], [
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
            ])
            ?>
            <?php Modal::end(); ?>
            <?php
            $this->registerJs('$(\'.modal-lg\').css(\'width\', \'90%\');'
                    . '$(function () { $("[data-toggle=\'tooltip\']").tooltip(); });'
                    . '$(function () { $("[data-toggle=\'popover\']").popover();});');
            ?>
</div>
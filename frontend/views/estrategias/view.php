<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\file\FileInput;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use app\models\Objetivos;
use app\models\Estrategias;
use \yii\db\Query;

/* @var $this yii\web\View */
/* @var $model app\models\Estrategias */
$objetivo = Objetivos::findOne(['id' => $model->id_objetivo]);

$this->title = 'Estrategia ' . $objetivo->numeracion . '.' . $model->numeracion . ': ' . $model->descripcion;
CrudAsset::register($this);
?>
<div class="estrategias-view">
    <div class="container-fluid">

            <div class="col-md-7">
                <h3>Objetivo: </h3> <p class="padre"><?= $model->getObjetivo($model->id_objetivo) ?></p>
                <h3>Estrategia: </h3><p class="descripcion"><?= $model->descripcion ?></p>
                <p>
                    <?= Html::a('Actualizar', ['update', 'id' => $model->id,], ['class' => 'btn btn-primary', 'role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip']) ?>

                    <?=
                    Html::a('Eliminar', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'role' => 'modal-remote', 'title' => 'Delete',
                        'data-confirm' => false, 'data-method' => false, // for overide yii data api
                        'data-request-method' => 'post',
                        'data-toggle' => 'tooltip',
                        'data-confirm-title' => 'Est&aacute;s Seguro?',
                        'data-confirm-message' => 'Est&aacute;s seguro de eliminar esto?'])
                    ?>
                </p>
            </div>
            <div class="col-md-5">
                <br>
                <?=
                DetailView::widget([
                    'model' => $model,
                    'class' => 'table table-striped table-bordered detail-view',
                    'attributes' => [
                        [
                            'attribute' => 'responsable',
                            'value' => dektrium\user\models\User::findOne(['id' => $model->responsable])->username,
                        ],
                        [
                            'attribute' => 'colaboradores',
                            'value' => $model->getColaboradores(array_map('intval', explode(',', $model->colaboradores))),
                        ],
                        'fecha_inicio',
                        'fecha_fin',
                        'presupuesto',
                    ],
                ])
                ?>
            </div>

    </div>
    <br><br>
    <div class="programas-index">
        <div id="ajaxCrudDatatable">
            <?=
            GridView::widget([
                'id' => 'crud-datatable',
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'pjax' => true,
                'columns' => require(__DIR__ . '/../programas' . '/_columns.php'),
                'toolbar' => [
                    ['content' =>
                        Html::a('<i class="glyphicon glyphicon-plus"></i>', ['programas/create', 'id' => $model->id], ['role' => 'modal-remote', 'title' => 'Crear nuevo Programa', 'class' => 'btn btn-default']) .
                        Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['', 'id' => $_GET['id']], ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Reset Grid']) .
                        //'{toggleData}'.
                        '{export}'
                    ],
                ],
                'striped' => true,
                'condensed' => true,
                'responsive' => true,
                'panel' => [
                    'type' => 'primary',
                    'heading' => '<i class="glyphicon glyphicon-list"></i> Programas',
                    //'before'=>'<em>* Resize table columns just like a spreadsheet by dragging the column edges.</em>',
                    'after' => BulkButtonWidget::widget([
                        'buttons' => Html::a('<i class="glyphicon glyphicon-trash"></i>&nbsp; Eliminar todo', ["programas/bulk-delete"], [
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
    $this->registerJs('$(\'.modal-lg\').css(\'width\', \'60%\');');
    ?>
</div>
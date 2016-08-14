<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\file\FileInput;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use app\models\Estrategias;
use \yii\db\Query;

/* @var $this yii\web\View */
/* @var $model app\models\Estrategias */
//'OBJETIVO ' . $model->getNumero($model->id);
$query = new Query();
$query->select('*')->from('numeracion_objetivo')->where(['id_objetivo' => $model->id_objetivo]);
$numeracionObjetivo = $query->createCommand()->queryOne();
$query2 = new Query();
$query2->select('*')->from('numeracion_estrategias')->where(['id_estrategia' => $model->id]);
$numeracionEstrategia = $query2->createCommand()->queryOne();
$this->title ='Objetivo '.$numeracionObjetivo['id'].': '. $model->getObjetivo($model->id_objetivo). ' Estrategia '.$numeracionEstrategia['numeracion'].': '.$model->descripcion;
$this->params['breadcrumbs'][] = ['label' => 'Objetivo ' . $numeracionObjetivo['id'], 'url' => ['/objetivos/view', 'id' => $model->id_objetivo]];
$this->params['breadcrumbs'][] = 'Estrategia ' . $numeracionEstrategia['numeracion'];
CrudAsset::register($this);
?>
<div class="estrategias-view">

    <h3>Objetivo: </h3> <p><?= $model->getObjetivo($model->id_objetivo) ?></p>
    <h3>Estrategia: </h3><p><?= $model->descripcion ?></p>
    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id,], ['class' => 'btn btn-primary', 'role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip']) ?>

        <?=
        Html::a('Delete', ['delete', 'id' => $model->id], [
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
                    [
                        'attribute' => 'responsables',
                        'value' => $model->getResponsables(array_map('intval', explode(',', $model->responsables))),
                    ],
                    'fecha_inicio',
                    'fecha_fin',
                    'presupuesto',
                ],
            ])
            ?>
        </div>
    </div>

    <h3>PROGRAMAS</h3>
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
    $this->registerJs('$(\'.modal-lg\').css(\'width\', \'95%\');');
    ?>
</div>
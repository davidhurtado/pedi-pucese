<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $model app\models\Objetivos */

$this->title = 'Objetivo ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Objetivos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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
                'columns' => require(__DIR__ . '/../estrategias' . '/_columns.php'),
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
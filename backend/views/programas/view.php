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

$estrategia = Estrategias::findOne(['id' => $model->id_estrategia]);
$objetivo = Objetivos::findOne(['id' => $estrategia->id_objetivo]);

$this->title = 'Programa ' . $objetivo->numeracion . '.' . $estrategia->numeracion . '.' . $model->numeracion . ': ' . $model->descripcion;
Yii::$app->params['titulo_exportacion'] = $this->title;
CrudAsset::register($this);
?>
<div class="estrategias-view">
    <div class="container-fluid">
        <div class="col-md-7">
            <h3>Estrategia: </h3><p class="padre"><?= $estrategia->descripcion ?></p>
            <h3>Programa: </h3><p class="descripcion"><?= $model->descripcion ?></p>
            <p>
                <?= Html::a('Actualizar', ['update', 'id' => $model->id,], ['class' => 'btn btn-primary', 'role' => 'modal-remote',]) ?>

                <?=
                Html::a('Eliminar', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'role' => 'modal-remote',
                    'data-confirm' => false, 'data-method' => false, // for overide yii data api
                    'data-request-method' => 'post',
                    'data-confirm-title' => 'Are you sure?',
                    'data-confirm-message' => 'Are you sure want to delete this item'])
                ?>
            </p>
        </div>
        <div class="col-md-5">
            <br>
            <?=
            DetailView::widget([
                'model' => $model,
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
    <div class="proyectos-index">
        <div id="ajaxCrudDatatable">
            <?=
            GridView::widget([
                'id' => 'crud-datatable',
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'pjax' => true,
                'columns' => require(__DIR__ . '/../proyectos' . '/_columns.php'),
                'toolbar' => [
                    ['content' =>
                        Html::a('<i class="glyphicon glyphicon-plus"></i>', ['proyectos/create', 'id' => $model->id], ['role' => 'modal-remote', 'title' => 'Crear nuevo Proyecto', 'class' => 'btn btn-default']) .
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
                    'heading' => '<i class="glyphicon glyphicon-list"></i> PROYECTOS',
                    //'before'=>'<em>* Resize table columns just like a spreadsheet by dragging the column edges.</em>',
                    'after' => BulkButtonWidget::widget([
                        'buttons' => Html::a('<i class="glyphicon glyphicon-trash"></i>&nbsp; Eliminar todo', ["proyectos/bulk-delete"], [
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
    $this->registerJs('$(\'.modal-lg\').css(\'width\', \'60%\');'
            . '$(function () { $("[data-toggle=\'tooltip\']").tooltip(); });'
            . '$(function () { $("[data-toggle=\'popover\']").popover();});');
    ?>
</div>
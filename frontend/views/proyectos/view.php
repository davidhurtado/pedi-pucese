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
use app\models\Programas;
use \yii\db\Query;

/* @var $this yii\web\View */
/* @var $model app\models\Proyectos */
$programa = Programas::findOne(['id' => $model->id_programa]);
$estrategia = Estrategias::findOne(['id' => $programa->id_estrategia]);
$objetivo = Objetivos::findOne(['id' => $estrategia->id_objetivo]);

$this->title = 'Proyecto ' . $objetivo->numeracion . '.' . $estrategia->numeracion . '.' . $programa->numeracion . '.' . $model->numeracion . ': ' . $model->descripcion;
Yii::$app->params['titulo_exportacion'] = $this->title;
CrudAsset::register($this);
?>
<div class="proyectos-view">
    <div class="container-fluid">
        <div class="col-md-7">
            <h3>Programa: </h3><p class="padre"><?= $programa->descripcion ?></p>
            <h3>Proyecto: </h3><p class="descripcion"><?= $model->descripcion ?></p>
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
                'columns' => require(__DIR__ . '/../subproyectos' . '/_columns.php'),
                'toolbar' => [
                    ['content' =>
                        Html::a('<i class="glyphicon glyphicon-plus"></i>', ['subproyectos/create', 'id' => $model->id], ['role' => 'modal-remote', 'title' => 'Crear nuevo Subproyecto', 'class' => 'btn btn-default']) .
                        Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['', 'id' => $_GET['id']], ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Reset Grid']) .
                        //'{toggleData}'.
                        '{export}'
                    ],
                ],
                'striped' => true,
                'condensed' => true,
                'responsive' => true,
                'summary'=>'',
                'panel' => [
                    'type' => 'primary',
                    //'heading' => '<i class="glyphicon glyphicon-list"></i> SUBPROYECTOS',
                    'before' => '<h4>SUBPROYECTOS</h4>',
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

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Estrategias */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Objetivo ' . $model->id_objetivo, 'url' => ['/objetivos/view', 'id' => $model->id_objetivo]];
$this->params['breadcrumbs'][] = 'Estrategia ' . $this->title;
?>
<div class="estrategias-view">

    <h3>Objetivo: </h3> <p><?= $model->getObjetivo($model->id_objetivo) ?></p>
    <h3>Estrategia: </h3><p><?= $model->descripcion ?></p>
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])
        ?>
    </p>
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-4">
                <?=
                DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'responsables',
                        'fecha_inicio',
                        'fecha_fin',
                        'presupuesto',
                    ],
                ])
                ?>
            </div>
            <div class="col-sm-8">
                <?=
                FileInput::widget([
                    'name' => 'evidencias',
                    'options' => [
                        'multiple' => true,
                        'showRemove' => false,
                    ],
                    'pluginOptions' => [
                        'overwriteInitial' => false,
                        'initialPreview' => $model->getEvidencias_preview(),
                        'initialPreviewAsData' => true,
                        'initialPreviewConfig' => $model->getEvidencias(),
                        'showPreview' => true,
                        'showCaption' => false,
                        'showRemove' => false,
                        'showUpload' => false,
                        'showBrowse' => false,
                        'showremoveClass' => false,
                        'showremoveIcon' => false
                    ]
                ]);
                ?>
            </div>
        </div>
    </div>
    <?php
    $this->registerJs('
             $(\'.modal-lg\').css(\'width\', \'90%\');
             $(\'.file-zoom-dialog\').css(\'padding-right"\', \'17px\');
    ');
    ?>
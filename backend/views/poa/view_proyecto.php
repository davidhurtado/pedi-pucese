<?php

use yii\widgets\DetailView;

use johnitvn\ajaxcrud\CrudAsset;
use app\models\Objetivos;
use app\models\Estrategias;
use app\models\Programas;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Proyectos */
$programa = Programas::findOne(['id' => $model->id_programa]);
$estrategia = Estrategias::findOne(['id' => $programa->id_estrategia]);
$objetivo = Objetivos::findOne(['id' => $estrategia->id_objetivo]);

$this->title = 'Proyecto ' . $objetivo->numeracion . '.' . $estrategia->numeracion . '.' . $programa->numeracion . '.' . $model->numeracion . ': ' . $model->descripcion;
$this->params['breadcrumbs'][] = ['data-toggle' => 'tooltip', 'data-placement' => 'bottom', 'title' => '[OBJETIVO] ' . $objetivo->descripcion, 'style' => 'cursor:default;', 'label' => 'Objetivo ' . $objetivo->numeracion, 'url' => ['/objetivos/view', 'id' => $objetivo->id]];
$this->params['breadcrumbs'][] = ['data-toggle' => 'tooltip', 'data-placement' => 'bottom', 'title' => '[ESTRATEGIA] ' . $estrategia->descripcion, 'style' => 'cursor:default;', 'label' => 'Estrategia ' . $estrategia->numeracion, 'url' => ['/estrategias/view', 'id' => $estrategia->id]];
$this->params['breadcrumbs'][] = 'Programa ' . $model->numeracion;
CrudAsset::register($this);
?>
<div class="proyectos-view">
    <h3>Programa: </h3><p><?= $programa->descripcion ?></p>
    <h3>Proyecto: </h3><p><?= $model->descripcion ?></p>
    <?php
    $form = ActiveForm::begin(['id' => 'proyecto','action' => ['poa/update-proyecto', 'id' => $model->id],'enableClientValidation' => true, 'enableAjaxValidation' => true]);
    $model->validate();
    ?>
    <p>
        <?= $form->field($model, 'estado')->dropDownList(['1' => 'Borrador', '2' => 'Ok', '3' => 'Ejecucion', '4' => 'Terminado'], ['options' => [3 => ['hidden' => true],4 => ['hidden' => true]]]) ?>
    </p>
    <?php
    ActiveForm::end();
    ?>
    <div class="col-sm-12">
        <div class="row">
            <?=
            DetailView::widget([
                'model' => $model,
                'attributes' => [
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
</div>

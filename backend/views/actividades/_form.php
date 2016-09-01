<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Actividades */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="actividades-form">

   <?php
    if ($controlador == 'suproyectos') {
        echo "<div class='descripcion-padre'><h4>" . "Proyecto " . $proyecto->numeracion . ": " . $proyecto->descripcion . "</h4></div>";
        if ($accion == 'create') {
            $form = ActiveForm::begin(['action' => ['actividades/create', 'id' => $id], 'id' => 'actividades']);
        } else {
            $form = ActiveForm::begin(['id' => 'actividad']);
        }
        ?>
    <?= $form->field($model, 'descripcion')->textarea(['rows' => 3, 'style' => 'resize:none'])  ?>

    <?= $form->field($model, 'codigo_presupuestario')->textInput(['maxlength' => false]) ?>

<?= $form->field($model, 'presupuesto')->textInput() ?>

    <div class="row" style="margin-bottom: 15px">
        <div class="col-sm-12">
            <?=
            DatePicker::widget([
                'language' => 'es',
                'model' => $model,
                'attribute' => 'fecha_inicio',
                'attribute2' => 'fecha_fin',
                'options' => ['placeholder' => 'Fecha  de inicio ...'],
                'options2' => ['placeholder' => 'Fecha de fin ...'],
                'type' => DatePicker::TYPE_RANGE,
                'form' => $form,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'startView' => 2,
                    'startDate' => $fechas->fecha_inicio,
                    'endDate' => $fechas->fecha_fin,
                ]
            ]);
            ?>
        </div>
    </div>


        <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

<?php
        ActiveForm::end();
    } else {
        if ($controlador == 'actividades') {
            ActiveForm::begin(['id' => 'actividad',]);
            echo Select2::widget([
                'name' => 'proyecto',
                'language' => 'es',
                'data' => ArrayHelper::map($model, 'id', 'descripcion'),
                'options' => ['placeholder' => 'Seleccione un Proyecto ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ActiveForm::end();
        }
    }
    ?>


</div>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Actividades */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="actividades-form">

    <?php
    if ($controlador == 'proyectos') {
        echo "<div class='descripcion-padre'><h4>" . "Proyecto " . $proyecto->numeracion . ": " . $proyecto->descripcion . "</h4></div>";
        if ($accion == 'create') {
            $form = ActiveForm::begin(['action' => ['actividades/create', 'id' => $id], 'id' => 'actividades']);
        } else {
            $form = ActiveForm::begin(['id' => 'actividad']);
        }
        ?>

        <?= $form->field($model, 'descripcion')->textarea(['rows' => 3, 'style' => 'resize:none']) ?>
        <div class="form-group field-actividades-codigo_presupuestario col-md-6 col-xs-12 col-sm-6 required">
            <?= $form->field($model, 'codigo_presupuestario')->textInput(['maxlength' => false]) ?>
        </div>
        <div class="form-group field-actividades-presupuesto col-md-6 col-xs-12 col-sm-6 required">
            <?= $form->field($model, 'presupuesto')->textInput() ?>
        </div>




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
                        'startDate' => $subproyecto->fecha_inicio,
                        'endDate' => $subproyecto->fecha_fin,
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

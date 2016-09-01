<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Subproyectos */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="subproyectos-form">
    <?= "<h4>" . "PROYECTO " . $proyecto->numeracion . ": " . $proyecto->descripcion . "</h4>" ?>
    <?php $form = ActiveForm::begin(); ?>
    <div class="row" style="margin-bottom: 15px">
        <div class="form-group field-proyectos-presupuesto col-md-6 col-xs-12 col-sm-6 required">
            <?= $form->field($model, 'estado')->dropDownList(['0' => 'Borrador', '1' => 'Ejecucion', '2' => 'Terminado']); ?>
        </div>
        <div class="form-group field-proyectos-presupuesto col-md-12 col-xs-12 col-sm-12 required">

            <?=
            DatePicker::widget([
                'language' => 'es',
                'model' => $model,
                'attribute' => 'fecha_inicio',
                'attribute2' => 'fecha_fin',
                'options' => ['placeholder' => 'Fecha  de inicio ...', 'disabled' => true,],
                'options2' => ['placeholder' => 'Fecha de fin ...', 'disabled' => true,],
                'type' => DatePicker::TYPE_RANGE,
                'form' => $form,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'startView' => 2,
                    'startDate' => $proyecto->fecha_inicio,
                    'endDate' => $proyecto->fecha_fin,
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

    <?php ActiveForm::end(); ?>

</div>

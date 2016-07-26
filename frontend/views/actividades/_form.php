<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
use \yii\db\Expression;

/* @var $this yii\web\View */
/* @var $model app\models\Actividades */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="actividades-form">


    <!--    ESTA PARTE PARTE PARA ACTIVAR EL AJAX EN EL FORMULARIO -->
    <?php
    $form = ActiveForm::begin([
                    //'enableAjaxValidation' => true,
    ]);
   
    ?>
    <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'codigo_presupuestario')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'presupuesto')->textInput() ?>
    <div class="row" style="margin-bottom: 8px">
        <div class="col-sm-6">
            <?=
            $form->field($model, 'fecha_inicio')->widget(DateTimePicker::className(), [
                'name' => 'fecha_inicio',
                'type' => DateTimePicker::TYPE_INPUT,
                'value' => $model->fecha_inicio,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd hh:ii:ss'
                ],
                    //'disabled' => true,
            ]);
            ?>   

        </div>
        <div class="col-sm-6">
            <?=
            $form->field($model, 'fecha_fin')->widget(DateTimePicker::className(), [
                'name' => 'fecha_fin',
                'type' => DateTimePicker::TYPE_INPUT,
                'value' => '',
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd hh:ii:ss'
                ],
            ]);
            ?>   
        </div>
    </div>  

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Objetivos */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="objetivos-form">

    <?php
    $form = ActiveForm::begin([
                'enableAjaxValidation' => true,
                'options' => ['enctype' => 'multipart/form-data'],
    ]);
    ?>


    <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'responsables')->textInput(['maxlength' => true]) ?>

    <div class="row" style="margin-bottom: 8px">
        <div class="col-sm-6">
            <?=
            $form->field($model, 'fecha_inicio')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'Fecha  de inicio ...'],
                'pluginOptions' => [
                    'autoclose' => true,
                     'format' => 'yyyy-m-dd'
                ]
            ]);
            ?>
        </div>
        <div class="col-sm-6">
            <?=
            $form->field($model, 'fecha_fin')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'Fecha de fin ...'],
                'pluginOptions' => [
                    'autoclose' => true,
                     'format' => 'yyyy-m-dd'
                ]
            ]);
            ?>
        </div>
    </div>

    <?=
    $form->field($model, 'evidencias')->widget(FileInput::classname(), [
        'options' => ['multiple' => true],
        'pluginOptions' => ['previewFileType' => 'any'],
        
    ]);
    ?> 

    <div class="form-group">
<?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Modificar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>
</div>

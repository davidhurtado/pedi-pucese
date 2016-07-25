<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\file\FileInput;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Estrategias */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="container">
    <div class="estrategias-form">

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
                'language' => 'es',
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'startView' => 2,
                    'minView' => 0,
                ]
            ]);
            ?>
        </div>
        <div class="col-sm-6">
            <?=
            $form->field($model, 'fecha_fin')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'Fecha de fin ...'],
                'language' => 'es',
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'startView' => 2,
                    'minView' => 0,
                ]
            ]);
            ?>
        </div>
    </div>
        <?=
        $form->field($model, 'evidencias[]')->widget(FileInput::classname(), [
            'options' => ['multiple' => true],
            'pluginOptions' => [
                'uploadUrl' => '/',
                'previewFileType' => 'any',
                'showPreview' => true,
                'showCaption' => true,
                'showRemove' => false,
                'showUpload' => false,
                'initialPreview' => $evidencias_preview,
                        'initialPreviewAsData' => true,
                        'initialPreviewConfig' => $evidencias,
                'overwriteInitial' => false,
                'maxFileSize' => 2800
            ]
        ]);
        ?> 

        <?= $form->field($model, 'presupuesto')->textInput() ?>


        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
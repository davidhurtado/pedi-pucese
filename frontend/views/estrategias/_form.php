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
        <?=
        DatePicker::widget([
            'name' => 'fecha_inicio',
            'value' => $model->FechaObjetivo->fecha_inicio,
            'type' => DatePicker::TYPE_RANGE,
            'name2' => 'fecha_fin',
            'value2' => $model->FechaObjetivo->fecha_fin,
            'pluginOptions' => [
                'autoclose' => false,
                'format' => 'yyyy-m-dd'
            ]
        ]);
        ?>
        <?=
        $form->field($model, 'evidencias')->widget(FileInput::classname(), [
            'options' => ['multiple' => true],
            'pluginOptions' => [
                'uploadUrl' => Url::to(['/site/file-upload']),
                'uploadExtraData' => [
                    'album_id' => 20,
                    'cat_id' => 'Nature'
                ],
                'maxFileCount' => 10
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
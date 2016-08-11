<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
use kartik\file\FileInput;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\Subproyectos */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="subproyectos-form">

    <?php $form = ActiveForm::begin([
                    'options' => ['enctype' => 'multipart/form-data'],
        ]); ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>

   <?=
        $form->field($model, 'evidencias[]')->widget(FileInput::classname(), [
            'options' => ['multiple' => true],
            'pluginOptions' => [
                'allowedFileExtensions' => ['pdf'],
                'uploadUrl' => '/',
                'previewFileType' => 'any',
                'showPreview' => true,
                'showCaption' => true,
                'showRemove' => true,
                'showUpload' => false,
                //'uploadAsync' => true,
                'initialPreview' => $evidencias_preview,
                'initialPreviewAsData' => true,
                'initialPreviewConfig' => $evidencias,
                'overwriteInitial' => false,
                'autoReplace' => true,
                'uploadClass'=>false
            ],
        ]);
        ?>

<div class="row" style="margin-bottom: 8px">
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
                    'startDate' =>$model->getFechas()->fecha_inicio,
                    'endDate'=>$model->getFechas()->fecha_fin,
                ]
            ]);
            ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

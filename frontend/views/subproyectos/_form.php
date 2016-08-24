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
                'showDelete'=> false,
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
    <div class="row" style="margin-bottom: 15px">
        <div class="col-sm-12">
            <?=
            DatePicker::widget([
                'language' => 'es',
                'model' => $model,
                'attribute' => 'fecha_inicio',
                'attribute2' => 'fecha_fin',
                'options' => ['placeholder' => 'Fecha  de inicio ...','disabled' => true,],
                'options2' => ['placeholder' => 'Fecha de fin ...','disabled' => true,],
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
 <?php
        $this->registerJs('
$(".kv-file-remove").on("click", function() {
    $.ajax({
        type: "GET",
        data: {
            action: "deletefile",
            file: $(this).data("url"),
            id:' . $model->id . ',
            fileName: $(this).data("key"),
        },
        url: "index.php?r=subproyectos/delete-document",
        success: function(msg) {
        $(".kv-fileinput-error").hide();
        alert("Exito");
        self.parent.location.reload(); 
        },
    })
})
    ');
        ?>
</div>

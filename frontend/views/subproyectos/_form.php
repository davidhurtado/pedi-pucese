<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\Subproyectos */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="subproyectos-form">
<?="<h4>"."PROYECTO ".$proyecto->numeracion.": ".$proyecto->descripcion."</h4>"?>
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'numeracion')->textInput() ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => false]) ?>

<?= $form->field($model, 'descripcion')->textInput(['maxlength' => false]) ?>

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

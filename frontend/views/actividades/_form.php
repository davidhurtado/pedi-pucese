<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Actividades */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="actividades-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_subproyecto')->textInput() ?>

    <?= $form->field($model, 'descripcion')->textarea(['rows' => 6, 'style' => 'resize:none'])  ?>

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

<?php ActiveForm::end(); ?>

</div>

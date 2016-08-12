<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Organigrama */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="organigrama-form">

    <?php $form = ActiveForm::begin(['id' => 'organigrama-form',]); ?>

    <?= $form->field($model, 'name')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'activo')->dropDownList(['0' => 'Desactivado', '1' => 'Activado',]); ?>
    <?php
    //$form->field($model, 'created')->textInput()
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

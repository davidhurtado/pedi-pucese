<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $model app\models\Niveles */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="niveles-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textarea(['rows' => 6]) ?>

    <?php
    //$form->field($model, 'rid')->textInput()
    ?>

    <?php
    echo $form->field($model, 'rid')->dropDownList(ArrayHelper::map($model->getAllLevels(),'nid','title'),
        ['prompt' => ' -- Sin nivel padre --']);
    ?>

    <?php
    //$form->field($model, 'org_id')->textInput()
    ?>

    <?php
    echo $form->field($model, 'org_id')->dropDownList(ArrayHelper::map($model->getOrgChart(),'id','name'),
        ['prompt' => ' -- Seleccione organigrama --']);
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Guardar Cambios', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

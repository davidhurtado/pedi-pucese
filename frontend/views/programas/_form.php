<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\Programas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="programas-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>

    <?php
    $model->responsables=array_map('intval', explode(',', $model->responsables));
     echo $form->field($model, 'responsables')->widget(Select2::className(), [
            'data' => ArrayHelper::map($model->getLevels(), 'nid', 'title'),
            'options' => [
                'id' => 'items',
                'multiple' => true,
                'tags' => true,
                'tokenSeparators' => [',', ' '],
            ],
        ]);
    ?>
 <div class="row" style="margin-bottom: 8px">
        <div class="col-sm-6">
            <?=
            $form->field($model, 'fecha_inicio')->widget(DateTimePicker::classname(), [
                'options' => ['placeholder' => 'Fecha  de inicio ...'],
                'language' => 'es',
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'startView' => 4,
                    'minView' => 2,
                ]
            ]);
            ?>
        </div>
        <div class="col-sm-6">
            <?=
            $form->field($model, 'fecha_fin')->widget(DateTimePicker::classname(), [
                'options' => ['placeholder' => 'Fecha de fin ...'],
                'language' => 'es',
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'startView' => 4,
                    'minView' => 2,
                ]
            ]);
            ?>
        </div>
    </div>

    <?= $form->field($model, 'presupuesto')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

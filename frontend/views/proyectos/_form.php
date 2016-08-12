<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\Proyectos */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="proyectos-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'proyectos-form',
    ]);
    ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descripcion')->textInput(['maxlength' => false]) ?>

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
        
        <label>Estado</label>
        <?= Html::activeDropDownList($model, 'p_status',
            array(1=>'Aceptado', 2=>'Borrador', 3 => 'En ejecucion'), ['class'=>'form-control']) ?>
        
        <hr>

    <div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>

</div>

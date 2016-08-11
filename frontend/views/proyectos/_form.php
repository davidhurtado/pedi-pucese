<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
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

        <?= $form->field($model, 'presupuesto')->textInput() ?>

    <div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['id'=>'crear_proyecto_','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>

</div>

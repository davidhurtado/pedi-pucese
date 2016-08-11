<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use app\models\Objetivos;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Objetivos */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="objetivos-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'objetivos-form',
    ]);
    ?>

    <?= $form->field($model, 'descripcion')->textarea(['rows' => 6, 'maxlength' => true, 'style' => 'resize:none']) ?>
    <?php
    $model->responsables = array_map('intval', explode(',', $model->responsables));
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
                    'startDate' => '2017-01-01',
                    'disableTouchKeyboard'=>false
                ]
            ]);
            ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Modificar', ['name' => 'crear_objetivo', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

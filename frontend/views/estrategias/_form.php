<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Estrategias */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="estrategias-form">

    <?php $form = ActiveForm::begin(['id'=>'estrategia']); ?>

    <?= $form->field($model, 'descripcion')->textarea(['rows' => 6, 'style' => 'resize:none']) ?>

    <?php
    if (Yii::$app->controller->action->id == 'update') {
        if ($model->validate()) {
            $model->responsables = array_map('intval', explode(',', $model->responsables));
        }
    }

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

    <?= $form->field($model, 'presupuesto')->textInput() ?>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>

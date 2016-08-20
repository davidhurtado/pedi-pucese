<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model frontend\models\Poa */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="poa-form">

    <?php $form = ActiveForm::begin(); ?>

    <?=
    DatePicker::widget([
        'language' => 'es',
        'model' => $model,
        'attribute' => 'fecha_ejecucion',
        'options' => ['placeholder' => 'Fecha  de ejecucion ...'],
        'form' => $form,
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd',
            'startView' => 2,
            'minViewMode' => 1,
            'startDate' => (date('Y') + 1) . '-01-01',
        ]
    ]);
    ?>

    <?php
    if (Yii::$app->controller->action->id == 'update') {
        echo $form->field($model, 'estado')->dropDownList(['0' => 'Borrador', '1' => 'Ejecucion', '2' => 'Terminado']);
    }
    ?>


        <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

<?php ActiveForm::end(); ?>

</div>

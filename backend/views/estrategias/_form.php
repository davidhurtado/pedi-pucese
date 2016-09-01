<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use app\models\Objetivos;

/* @var $this yii\web\View */
/* @var $model app\models\Estrategias */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="estrategias-form">
    <?php
    if ($controlador == 'objetivos') {
        echo "<div class='descripcion-padre'><h4>" . "OBJETIVO " . $objetivo->numeracion . ": " . $objetivo->descripcion . "</h4></div>";
        if ($accion == 'create') {
            $form = ActiveForm::begin(['action' => ['estrategias/create', 'id' => $id], 'id' => 'estrategia']);
        } else {
            $form = ActiveForm::begin(['id' => 'estrategia']);
        }
        ?>
        <div class="form-group field-estrategias-numeracion col-md-2 col-xs-12 col-sm-4 required">
            <?= $form->field($model, 'numeracion')->textInput() ?>
        </div>
        <div class="form-group field-estrategias-descripcion col-md-10 col-xs-12 col-sm-8 required">
            <?= $form->field($model, 'descripcion')->textarea(['rows' => 4, 'maxlength' => false, 'style' => 'resize:none']) ?>
        </div>
        <?php
        $request = Yii::$app->request;
        if (Yii::$app->controller->action->id == 'update') {
            if (!$model->load($request->post())) {
                $model->colaboradores = array_map('intval', explode(',', $model->colaboradores));
            }
        }

        echo $form->field($model, 'colaboradores')->widget(Select2::className(), [
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
                        'startDate' => $objetivo->fecha_inicio,
                        'endDate' => $objetivo->fecha_fin,
                    ]
                ]);
                ?>
            </div>
        </div>
        <div class="form-group field-estrategias-presupuesto col-md-6 col-xs-12 col-sm-6 required">
            <?= $form->field($model, 'presupuesto')->textInput() ?>
        </div>
        <div class="form-group field-estrategias-validacion col-md-6 col-xs-12 col-sm-6 required">
            <?= $form->field($model, 'validacion')->dropDownList(['0' => 'Desactivado', '1' => 'Activado',]); ?>
        </div>

        <?php if (!Yii::$app->request->isAjax) { ?>
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        <?php } ?>

        <?php
        ActiveForm::end();
    } else {
        if ($controlador == 'estrategias') {
            $form = ActiveForm::begin(['id' => 'estrategia',]);
            echo Select2::widget([
                'name' => 'objetivo',
                'language' => 'es',
                'data' => ArrayHelper::map($model, 'id', 'descripcion'),
                'options' => ['placeholder' => 'Seleccione un Objetivo ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ActiveForm::end();
        }
    }
    ?>

</div>

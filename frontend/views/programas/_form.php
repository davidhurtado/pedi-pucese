<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\models\Estrategias;

/* @var $this yii\web\View */
/* @var $model app\models\Programas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="programas-form">
    <?php
    if ($controlador == 'estrategias') {
        echo "<div class='descripcion-padre'><h4>" . "ESTRATEGIA " . $estrategia->numeracion . ": " . $estrategia->descripcion . "</h4></div>";
        if ($accion == 'create') {
            $form = ActiveForm::begin(['action' => ['programas/create','id'=>$id], 'id' => 'programa']);
        } else {
            $form = ActiveForm::begin(['id' => 'programa']);
        }
        ?>
    <div class="form-group field-programas-numeracion col-md-2 col-xs-12 col-sm-4 required">
        <?= $form->field($model, 'numeracion')->textInput() ?>
    </div>
    <div class="form-group field-programas-descripcion col-md-10 col-xs-12 col-sm-8 required">
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
                        'startDate' => $estrategia->fecha_inicio,
                        'endDate' => $estrategia->fecha_fin,
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
        <?php
        }
        ActiveForm::end();
    } else {
        if ($controlador == 'programas') {
            ActiveForm::begin(['id' => 'programa',]);
            echo Select2::widget([
                'name' => 'estrategia',
                'language' => 'es',
                'data' => ArrayHelper::map($model, 'id', 'descripcion'),
                'options' => ['placeholder' => 'Seleccione una Estrategia ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ActiveForm::end();
        }
    }
    ?>

</div>

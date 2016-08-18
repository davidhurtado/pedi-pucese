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
    if ($controlador == 'programas') {
        echo "<h4>" . "PROGRAMA " . $programa->numeracion . ": " . $programa->descripcion . "</h4>";
        if ($accion == 'create') {
            $form = ActiveForm::begin(['action' => ['proyectos/create', 'id' => $id], 'id' => 'proyecto']);
        } else {
            $form = ActiveForm::begin(['id' => 'proyecto']);
        }
        ?>
    <?= $form->field($model, 'numeracion')->textInput() ?>
     <?= $form->field($model, 'nombre')->textarea(['rows' => 2, 'style' => 'resize:none']) ?>

    <?= $form->field($model, 'descripcion')->textarea(['rows' => 6, 'style' => 'resize:none']) ?>

    <?php
    $request = Yii::$app->request;
    if (Yii::$app->controller->action->id == 'update') {
         if (!$model->load($request->post())) {
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
                    'startDate' => $programa->fecha_inicio,
                    'endDate' => $programa->fecha_fin,
                ]
            ]);
            ?>
        </div>
    </div>

        <?= $form->field($model, 'presupuesto')->textInput() ?>


  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

         <?php
        ActiveForm::end();
    } else {
        if ($controlador == 'proyectos') {
            ActiveForm::begin(['id' => 'proyecto',]);
            echo Select2::widget([
                'name' => 'programa',
                'language' => 'es',
                'data' => ArrayHelper::map($model, 'id', 'descripcion'),
                'options' => ['placeholder' => 'Seleccione un Programa ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ActiveForm::end();
        }
    }
    ?>
    
</div>

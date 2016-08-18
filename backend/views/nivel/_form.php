<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use \yii\db\Query;

$query = new Query();
$query->select('*')->from('niveles');
$cmd = $query->createCommand()->queryAll();
$query_org = new Query();
$query_org->select('*')->from('organigrama');
$organigrama = $query_org->createCommand()->queryAll();
/* @var $this yii\web\View */
/* @var $model app\models\Niveles */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="niveles-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textarea(['rows' => 3,'style' => 'resize:none']) ?>

    <?=$form->field($model, 'rid')->widget(Select2::classname(), [
    'data' => ArrayHelper::map($cmd, 'nid', 'title'),
    'options' => ['placeholder' => 'Seleccione nivel superior'],
    'pluginOptions' => [
        'allowClear' => true
    ],
]) ?>

   <?=$form->field($model, 'org_id')->widget(Select2::classname(), [
    'data' => ArrayHelper::map($organigrama, 'id', 'name'),
    'options' => ['placeholder' => 'Seleccione organigrama'],
    'pluginOptions' => [
        'allowClear' => true
    ],
]) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>

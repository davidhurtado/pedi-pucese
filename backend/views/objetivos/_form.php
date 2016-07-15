<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker
/* @var $this yii\web\View */
/* @var $model app\models\Objetivos */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="objetivos-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'responsables')->textInput(['maxlength' => true]) ?>

    
     
     <?php echo $form->field($model,'fecha_inicio')->
    widget(DatePicker::className(),[
       'name' => 'fecha_inicio',
        'type' => DatePicker::TYPE_COMPONENT_PREPEND,
        'value' => '13-07-2016',
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd'
        ]
    ]) ?>     

    <?php echo $form->field($model,'fecha_fin')->
    widget(DatePicker::className(),[
       'name' => 'fecha_fin',
        'type' => DatePicker::TYPE_COMPONENT_PREPEND,
        'value' => '13-07-2016',
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd'
        ]
    ]) ?>


    <?= $form->field($model, 'evidencias')->textInput(['maxlength' => true]) ?>
    
    
       <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    
    <?php ActiveForm::end(); ?>
    
    </div>


    
</div

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
$time = new \DateTime('now', new \DateTimeZone('America/Guayaquil'));
$currentDate = $time->format('Y-m-d');
?>

<div class="objetivos-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'objetivos-signup',
                'enableClientValidation' => true,
                    //'enableAjaxValidation'=>true,
    ]);
    ?>
    <div class="form-group field-objetivos-numeracion col-md-2 col-xs-12 col-sm-4 required">
        <?= $form->field($model, 'numeracion')->textInput() ?>
    </div>
    <div class="form-group field-objetivos-descripcion col-md-10 col-xs-12 col-sm-8 required">
        <?= $form->field($model, 'descripcion')->textarea(['rows' => 4, 'maxlength' => false, 'style' => 'resize:none']) ?>
    </div>

    <?php
    if (Yii::$app->controller->action->id == 'update') {
        if ($model->validate()) {
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
                   'startDate' => (date ('Y')+1).'-01-01',
                //'startDate' => date ( 'Y-m-d' , strtotime ( '+1 year' , strtotime ( $currentDate ))),
                ]
            ]);
            ?>
        </div>
    </div>


        <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

<?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

use kartik\datetime\DateTimePicker;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Programas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="programas-index">
    <div class="row">
        <div class="col-sm-4">
        <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-sm-8">
        </div>
    </div>
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-6">

            </div>
            <div class="col-sm-6">
                <!--?= $form->field($model, 'id')->dropDownList([])->label('Copiadora') ?-->
                <?php
                $form = ActiveForm::begin([
                            'enableAjaxValidation' => true,
                            'options' => ['enctype' => 'multipart/form-data'],
                            'action' => 'index.php?r=programas',
                            'method' => 'get',
                ]);
                ?>
                <div class="col-xs-6">
                    <?=
                    DateTimePicker::widget([
                        'name' => 'anio',
                        'language' => 'es',
                        'options' => ['placeholder' => 'Seleccione Año'],
                        'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                        'pluginOptions' => [
                            'format' => 'yyyy',
                            'autoclose' => true,
                            'startView' => 4,
                            'minView' => 4,
                        ],
                    ])
                    ?>
                </div>
                <div class="col-xs-6">
                    <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
    <?php $dataProvider->pagination->pageSize = 10; ?>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'class' => 'yii\grid\CheckboxColumn',
                'name' => 'id'
            ],

            //'id',
            //'id_estrategia',
            'descripcion',
            //'responsables',
            [
                'attribute' => 'fecha_inicio',
                'value' => 'fecha_inicio',
                'filter' => false,
            ],
            [
                'attribute' => 'fecha_fin',
                'value' => 'fecha_fin',
                'filter' => false
            ],
            // 'presupuesto',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

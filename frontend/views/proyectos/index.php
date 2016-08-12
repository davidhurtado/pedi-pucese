<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\datetime\DateTimePicker;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Proyectos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="proyectos-index">

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
                            'action' => 'index.php?r=proyectos',
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
            //'id_programa',
            'nombre',
            'descripcion' => [
                'label' => 'Descripcion',
                'attribute' => 'descripcion',
                'class' => 'yii\grid\DataColumn',
                'value' => function($data){
                    return $data->truncDesc();
                },
                'filter' => true,
                'format' => 'raw'
            ],
            'p_status' => [
                'label' => 'Estado',
                'attribute' => 'p_status',
                'format' => 'raw',
                'value' => function($data){
                    // estados :: mx1 -->
                    $classEstados = array(1=>'success', 2=>'warning', 3=>'info', 0 => 'danger');
                    $pr = ($data->p_status == 2 ? 'borrador' : ($data->p_status == 1 ? 'aceptado' : ($data->p_status == 3 ? 'en ejecucion' : 'NaN')));

                    return Html::tag('span', ucfirst($pr), ['class' => 'label label-'.$classEstados[$data->p_status]] );

                },
                'filter' => true


            ],
            //'descripcion' => '$data->truncDesc()',
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
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{subprojects} {view} {update} {delete}',
                'buttons' => [
                    'subprojects' => function ($url, $model) {
                        return Html::tag( 'span', '',
                            [
                                'class' => 'glyphicon glyphicon-list',
                                'alt' => 'Ver subproyectos',
                                'data-title'=>'Subproyectos',
                                'data-content'=> $model->listSubProjects(),
                                'data-toggle'=>'popover',

                                'style'=>'text-decoration: underline; cursor:pointer;'
                            ]
                            
                        );
                    },
                    'format' => 'raw',
                ]

            ],
        ],
    ]);
    ?>
</div>

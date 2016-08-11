<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use kartik\datetime\DateTimePicker;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ObjetivosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Objetivos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="objetivos-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-6">
                <p>
                    <?=
                    Html::button('Crear Objetivos', [
                        'id' => 'crear_objetivo_modal',
                        //'name'=>'crear-objetivo',
                        'class' => 'btn btn-success btn-ajax-modal',
                        'value' => Url::to(['/objetivos/create']),
                        'data-target' => '#modal_add_objetivos',
                    ]);
                    ?>                </p>
            </div>
            <div class="col-sm-6">
                <!--?= $form->field($model, 'id')->dropDownList([])->label('Copiadora') ?-->
                <?php
                $form = ActiveForm::begin([
                            'enableAjaxValidation' => true,
                            'options' => ['enctype' => 'multipart/form-data'],
                            'action' => 'index.php?r=objetivos',
                            'method' => 'get',
                ]);
                ?>
                <div class="col-xs-10">
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
                <div class="col-xs-2">
                    <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'summary'=>'.', 
        'filterModel' => $searchModel,
        
        'columns' => [
           /* ['class' => 'yii\grid\SerialColumn'],
            [
                'class' => 'yii\grid\CheckboxColumn',
                'name' => 'id'
            ],*/
            'descripcion',
            //'evidencias',
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
            // 'evidencias',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    /*
      GridView::widget([
      'id' => 'kv-grid-demo',
      'dataProvider' => $dataProvider,
      'filterModel' => $searchModel,
      //'columns' => $gridColumns,
      'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
      'headerRowOptions' => ['class' => 'kartik-sheet-style'],
      'filterRowOptions' => ['class' => 'kartik-sheet-style'],
      'pjax' => true, // pjax is set to always true for this demo
      // set your toolbar
      'toolbar' => [
      ['content' =>
      Html::button('<i class="glyphicon glyphicon-plus"></i>', ['type' => 'button', 'title' => 'Add Book', 'class' => 'btn btn-success', 'onclick' => 'alert("This will launch the book creation form.\n\nDisabled for this demo!");']) . ' '.
      Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['grid-demo'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => 'Reset Grid'])
      ],
      '{export}',
      '{toggleData}',
      ],
      // set export properties
      'export' => [
      'fontAwesome' => true
      ],
      // parameters from the demo form
      'panel' => [
      'type' => GridView::TYPE_PRIMARY,
      ],
      'persistResize' => false,
      //'exportConfig' => $exportConfig,
      ]);
     */
    ?>
    <?php
    Modal::begin([
        'size' => Modal::SIZE_LARGE,
        'id' => 'modal_add_objetivos',
        'header' => '<h4>Objetivos</h4>',
    ]);
    echo '<div id="modal-content"></div>';
    Modal::end();
    ?>
    <?php
    $this->registerJs('
             $(\'.modal-lg\').css(\'width\', \'90%\');
        $(\'.btn-ajax-modal\').click(function (){
    var elm = $(this),
        target = elm.attr(\'data-target\'),
        ajax_body = elm.attr(\'value\');

    $(target).modal(\'show\')
        .find(\'.modal-content\')
        .load(ajax_body);
});

    ');
    ?>
</div>

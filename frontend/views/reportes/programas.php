<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use app\models\Objetivos;
use app\models\Estrategias;
use app\models\Programas;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ObjetivosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Programas';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);
?>
<div class="objetivos-index">
    <div id="ajaxCrudDatatable">
        <?php //var_dump($dataProvider->query->one()->getObjetivos()->all()[0]->descripcion) ?>
        <?=
        GridView::widget([
            'id' => 'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $search,
            'pjax' => true,
            'panel'=>['type'=>'primary', 'heading'=>'Objetivos y Estrategias'],
            'columns' => [
                //['class' => 'kartik\grid\SerialColumn'],
                [
                    'attribute' => 'id',
                    'width' => '310px',
                    'value' => function ($model, $key, $index, $widget) {
            
                        return 'Estrategia:'.  Estrategias::findOne($model->id_estrategia)->descripcion;
                    },
                    'group' => true, // enable grouping,
                    'groupedRow' => true, // move grouped column to a single grouped row
                    'groupOddCssClass' => 'kv-grouped-row', // configure odd group cell css class
                    'groupEvenCssClass' => 'kv-grouped-row', // configure even group cell css class
                ],
                [
                    'attribute' => 'id_programa',
                    'width' => '250px',
                    'value' => function ($model, $key, $index, $widget) {
                        return $model->id_estrategia.'.'.($index+1).'. '.$model->descripcion;
                    },
                    'group' => true, // enable grouping
                    'subGroupOf' => 1 // supplier column index is the parent group
                ],
                
            ],
            'toolbar' => [
                ['content' =>
                    Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'], ['role' => 'modal-remote', 'title' => 'Crear Objetivo', 'class' => 'btn btn-default']) .
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''], ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Reset Grid']) .
                    '{toggleData}' .
                    '{export}'
                ],
            ],
            'striped' => true,
            'condensed' => true,
            'responsive' => true,
            'panel' => [
                'type' => 'primary',
                'after' => BulkButtonWidget::widget([
                    'buttons' => Html::a('<i class="glyphicon glyphicon-trash"></i>&nbsp; Eliminar todo', ["bulk-delete"], [
                        "class" => "btn btn-danger btn-xs",
                        'role' => 'modal-remote-bulk',
                        'data-confirm' => false, 'data-method' => false, // for overide yii data api
                        'data-request-method' => 'post',
                        'data-confirm-title' => 'Est&aacute;s Seguro?',
                        'data-confirm-message' => 'Est&aacute;s seguro de eliminar esto?'
                    ]),
                ]) .
                '<div class="clearfix"></div>',
            ]
        ])
        ?>
    </div>
</div>

<?php
Modal::begin([
    'size' => Modal::SIZE_LARGE,
    "id" => "ajaxCrudModal",
    "footer" => "", // always need it for jquery plugin
])
?>
<?php Modal::end(); ?>
<?php
$this->registerJs('$(\'.modal-lg\').css(\'width\', \'90%\');');
?>
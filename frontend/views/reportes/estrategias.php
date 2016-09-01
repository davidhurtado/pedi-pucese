<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use app\models\Estrategias;
use app\models\Objetivos;
use app\models\Programas;
use \yii\db\Query;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ObjetivosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Objetivos y Estrategias';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);
?>
<div class="estrategias-index">
    <div id="ajaxCrudDatatable">
        <?=
        GridView::widget([
            'id' => 'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $search,
            'pjax' => true,
            'striped' => true,
            'hover' => true,
            'toolbar' => [
                ['content' =>
                    //Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'], ['role' => 'modal-remote', 'title' => 'Crear Objetivo', 'class' => 'btn btn-default']) .
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''], ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Reset Grid']) .
                    '{toggleData}' .
                    '{export}'
                ],
            ],
            'panel' => [
                'type' => 'primary', 
                'heading' => '<i class="glyphicon glyphicon-list"></i> ESTRATEGIAS',
                'before' => '<h4>VISTA GENERAL DE ESTRATEGIAS.</h4>',
            ],
            'columns' => [
                //['class' => 'kartik\grid\SerialColumn'],
                [
                    'attribute' => 'id',
                    'width' => '310px',
                    'value' => function ($model, $key, $index, $widget) {
                        $objetivo = Objetivos::findOne($model->id_objetivo);
                        return 'Objetivo ' . $objetivo->numeracion . ': ' . $objetivo->descripcion;
                    },
                    'group' => true, // enable grouping,
                    'groupedRow' => true, // move grouped column to a single grouped row
                    'groupOddCssClass' => 'kv-grouped-row', // configure odd group cell css class
                    'groupEvenCssClass' => 'kv-grouped-row', // configure even group cell css class
                ],
                [
                    'attribute' => 'id_objetivo',
                    'width' => '250px',
                    'label' => 'Objetivos y Estrategias',
                    'value' => function ($model, $key, $index, $widget) {
                        $estrategia = Estrategias::findOne($model->id);
                        $objetivo = Objetivos::findOne($model->id_objetivo);
                        return $objetivo->numeracion . '.' . $estrategia->numeracion . ': ' . $model->descripcion;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map(Objetivos::find()->where(['validacion'=>1])->orderBy('id')->asArray()->all(), 'id', 'descripcion'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Seleccionar Objetivo'],
                    'group' => true, // enable grouping
                    'subGroupOf' => 1, // supplier column index is the parent group
                ],
            ],
        ])
        ?>
    </div>
</div>
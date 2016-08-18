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

$this->title = 'Objetivos';
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
            'striped' => false,
            'hover' => true,
            'panel' => ['type' => 'primary', 'heading' => 'Objetivos y Estrategias'],
            'columns' => [
                //['class' => 'kartik\grid\SerialColumn'],
                [
                    'attribute' => 'id',
                    'width' => '310px',
                    'value' => function ($model, $key, $index, $widget) {
                        $objetivo = Objetivos::findOne($model->id);
                        return 'Objetivo ' . $objetivo->numeracion . ': ' . $objetivo->descripcion;
                    },
                    'group' => true, // enable grouping,
                    'subGroupOf' => 1, // supplier column index is the parent group
                    'groupedRow' => true, // move grouped column to a single grouped row
                    'groupOddCssClass' => 'kv-grouped-row', // configure odd group cell css class
                    'groupEvenCssClass' => 'kv-grouped-row', // configure even group cell css class
                ],
                [
                    'attribute' => 'id-obj',
                    'width' => '250px',
                    'value' => function ($model, $key, $index, $widget) {
                        $estrategia = Estrategias::findOne(['id_objetivo' => $model->id]);
                        //$objetivo = Objetivos::findOne($model->id_objetivo);
                        if($estrategia!=null){
                            return $model->numeracion . '.' . $estrategia->numeracion . ': ' . $model->descripcion;
                        }else{
                            return 'Sin Estrategias';
                        }
                        
                    },
                            'group' => true, // enable grouping
                            'subGroupOf' => 2, // supplier column index is the parent group
                            'groupedRow' => true, // move grouped column to a single grouped row
                        ],
                    ],
                ])
                ?>
    </div>
</div>
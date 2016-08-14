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
            'striped' => false,
            'hover' => true,
            'panel' => ['type' => 'primary', 'heading' => 'Objetivos y Estrategias'],
            'columns' => [
                //['class' => 'kartik\grid\SerialColumn'],
                [
                    'attribute' => 'id',
                    'width' => '310px',
                    'value' => function ($model, $key, $index, $widget) {
                        $query = new Query();
                        $query->select('*')->from('numeracion_objetivo')->where(['id_objetivo' => $model->id_objetivo]);
                        $numeracion = $query->createCommand()->queryOne();
                        return 'Objetivo ' .$numeracion['id'].': '. Objetivos::findOne($model->id_objetivo)->descripcion;
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
                                $query = new Query();
                                $query2 = new Query();
                                $query->select('*')->from('numeracion_objetivo')->where(['id_objetivo' => $model->id_objetivo]);
                                $numeracionObjetivo = $query->createCommand()->queryOne();
                                
                                $query2->select('*')->from('numeracion_estrategias')->where(['id_estrategia' => $model->id]);
                                $numeracionEstrategia = $query2->createCommand()->queryOne();
                                
                                return $numeracionObjetivo['id'] . '.' . $numeracionEstrategia['numeracion'] . '.: ' . $model->descripcion;
                            },
                                    'filterType' => GridView::FILTER_SELECT2,
                                    'filter' => ArrayHelper::map(Objetivos::find()->orderBy('id')->asArray()->all(), 'id', 'descripcion'),
                                    'filterWidgetOptions' => [
                                        'pluginOptions' => ['allowClear' => true],
                                    ],
                                    'filterInputOptions' => ['placeholder' => 'Seleccionar Objetivo'],
                                    'group' => true, // enable grouping
                                    'subGroupOf' => 1, // supplier column index is the parent group
                                ],
                                [
                                    'attribute' => 'id_objetivo',
                                    'width' => '250px',
                                    'label' => 'Presupuesto',
                                    'value' => function ($model, $key, $index, $widget) {
                                        return $model->presupuesto;
                                    },
                                    'filterType' => GridView::FILTER_SELECT2,
                                    'filter' => ArrayHelper::map(Objetivos::find()->orderBy('id')->asArray()->all(), 'id', 'descripcion'),
                                    'filterWidgetOptions' => [
                                        'pluginOptions' => ['allowClear' => true],
                                    ],
                                    'filterInputOptions' => ['placeholder' => 'Seleccionar Objetivo'],
                                    'group' => true, // enable grouping
                                    'subGroupOf' => 2 // supplier column index is the parent group
                                ],
                            ],
                        ])
                        ?>
    </div>
</div>
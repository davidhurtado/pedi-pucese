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
                'heading' => '<i class="glyphicon glyphicon-list"></i> OBJETIVOS',
                'before' => '<h4>VISTA GENERAL DE OBJETIVOS.</h4>',
            ],
            'columns' => [
                //['class' => 'kartik\grid\SerialColumn'],
                [
                    'attribute' => 'id',
                    'label'=>'DESCRIPCION',
                    'width' => '310px',
                    'value' => function ($model, $key, $index, $widget) {
                        $objetivo = Objetivos::findOne($model->id);
                        return 'Objetivo ' . $objetivo->numeracion . ': ' . $objetivo->descripcion;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map(Objetivos::find()->where(['validacion'=>1])->orderBy('id')->asArray()->all(), 'id', 'descripcion'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Seleccionar Objetivo'],
                ],
            ],
        ])
        ?>
    </div>
</div>
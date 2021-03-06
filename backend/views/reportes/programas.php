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
use \yii\db\Query;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ObjetivosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Programas';
$this->params['breadcrumbs'][] = $this->title;
Yii::$app->params['titulo_exportacion'] = $this->title;
CrudAsset::register($this);
?>
<div class="programas-index">
    <div id="ajaxCrudDatatable">
        <?php //var_dump($dataProvider->query->one()->getObjetivos()->all()[0]->descripcion) ?>
        <?=
        GridView::widget([
            'id' => 'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $search,
            'pjax' => true,            
            'columns' => [
                //['class' => 'kartik\grid\SerialColumn'],
                [
                    //'attribute' => 'id',
                    'width' => '310px',
                    'value' => function ($model, $key, $index, $widget) {
                        $estrategia = Estrategias::findOne($model->id_estrategia);
                        $objetivo = Objetivos::findOne($estrategia->id_objetivo);
                        return 'Objetivo ' . $objetivo->numeracion . ': ' . $objetivo->descripcion;
                    },
                    'group' => true, // enable grouping,
                    'subGroupOf' => 0, // supplier column index is the parent group
                    'groupedRow' => true, // move grouped column to a single grouped row
                    'groupOddCssClass' => 'kv-grouped-row', // configure odd group cell css class
                    'groupEvenCssClass' => 'kv-grouped-row', // configure even group cell css class
                ],
                [
                    'attribute' => 'id',
                    'width' => '310px',
                    'value' => function ($model, $key, $index, $widget) {
                        $estrategia = Estrategias::findOne($model->id_estrategia);
                        $objetivo = Objetivos::findOne($estrategia->id_objetivo);
                        return 'Estrategia ' . $objetivo->numeracion . '.' . $estrategia->numeracion . ': ' . $estrategia->descripcion;
                    },
                    'group' => true, // enable grouping,
                    'subGroupOf' => 1, // supplier column index is the parent group
                    'groupedRow' => true, // move grouped column to a single grouped row
                    'groupOddCssClass' => 'kv-grouped-row', // configure odd group cell css class
                    'groupEvenCssClass' => 'kv-grouped-row', // configure even group cell css class
                ],
                [
                    'attribute' => 'id_estrategia',
                    'label' => '',
                    'width' => '250px',
                    'value' => function ($model, $key, $index, $widget) {
                        $estrategia = Estrategias::findOne($model->id_estrategia);
                        $objetivo = Objetivos::findOne($estrategia->id_objetivo);
                        return $objetivo->numeracion . '.' . $estrategia->numeracion . '.' . $model->numeracion . ': ' . $model->descripcion;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map(Estrategias::find()->orderBy('id')->asArray()->all(), 'id', 'descripcion'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Seleccionar Estrategia'],
                    'group' => true, // enable grouping
                    'subGroupOf' => 2 // supplier column index is the parent group
                ],
            ],
            'toolbar' => [
                ['content' =>
                    //Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'], ['role' => 'modal-remote', 'title' => 'Crear Objetivo', 'class' => 'btn btn-default']) .
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
                'heading' => '<i class="glyphicon glyphicon-list"></i> PROGRAMAS',
                'before' => '<h4>VISTA GENERAL DE PROGRAMAS.</h4>',
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
$this->registerJs('$(\'.modal-lg\').css(\'width\', \'60%\');');
?>
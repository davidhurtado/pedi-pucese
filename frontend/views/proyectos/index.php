<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProyectosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Proyectos';
Yii::$app->params['titulo_exportacion'] = $this->title;
CrudAsset::register($this);
?>
<div class="proyectos-index">
    <div id="ajaxCrudDatatable">
        <?=
        GridView::widget([
            'id' => 'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax' => true,
            'columns' => require(__DIR__ . '/_columns.php'),
            'toolbar' => [
                ['content' =>
                    Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create-index'], ['role' => 'modal-remote', 'title' => 'Crear nuevo Proyecto', 'class' => 'btn btn-default']) .
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''], ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Reset Grid']) .
                    //'{toggleData}' .
                    '{export}'
                ],
            ],
            'striped' => true,
            'condensed' => true,
            'responsive' => true,
            'panel' => [
                'type' => 'primary',
                'heading' => '<i class="glyphicon glyphicon-list"></i> PROYECTOS',
                'before' => '<h4>INGRESE SUS PROYECTOS.</h4>',
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
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],
])
?>
<?php Modal::end(); ?>
<?php
$this->registerJs('$(\'.modal-lg\').css(\'width\', \'60%\');'
        . '$(function () { $("[data-toggle=\'tooltip\']").tooltip(); });'
        . '$(function () { $("[data-toggle=\'popover\']").popover();});');
?>

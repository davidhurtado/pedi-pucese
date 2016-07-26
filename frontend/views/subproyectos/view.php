<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Subproyectos */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Subproyectos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subproyectos-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])
        ?>
    </p>
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-7">
                <?=
                DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'nombre',
                        'descripcion',
                        'fecha_inicio',
                        'fecha_fin',
                    ],
                ])
                ?>
            </div>
            <div class="col-sm-3">
                <?=
                Html::button('Ver Evidencias', [
                    'class' => 'btn btn-success btn-ajax-modal',
                    'data-target' => '#modal_ver_evidencia',
                ]);
                ?>
            </div>
        </div>
    </div>
    <h3>ACTIVIDADES</h3>
    <?= Html::a('Crear Actividades', Url::to(['/actividades/index', 'pid' => $model->id]), ['class' => 'btn btn-success']) ?>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            //'id',
            ['class' => 'yii\grid\SerialColumn'],
            'descripcion',
            'codigo_presupuestario',
            //'fecha_inicio',
            //'fecha_fin',
            // 'evidencias',
            'presupuesto',
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view}{update}{delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['actividades/view', 'id' => $model['id']], [
                                    'title' => Yii::t('yii', 'Ver'),
                        ]);
                    },
                            'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['actividades/update', 'id' => $model['id']], [
                                    'title' => Yii::t('yii', 'Modificar'),
                        ]);
                    },
                            'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['actividades/delete', 'id' => $model['id']], [
                                    'title' => Yii::t('yii', 'Eliminar'),
                        ]);
                    },
                        ]
                    ],
                ],
            ]);
            ?>
            <?php
            Modal::begin([
                'size' => Modal::SIZE_LARGE,
                'id' => 'modal_ver_evidencia',
                'header' => '<h4>Evidencias</h4>',
            ]);
            echo FileInput::widget([
                'name' => 'evidencias',
                'options' => [
                    'multiple' => true,
                    'showRemove' => false,
                    'layoutTemplates' => [
                        'main2' => '<div class="kv-upload-progress hide"></div>{remove}{cancel}{upload}{preview}',
                    ]
                ],
                'pluginOptions' => [
                    'overwriteInitial' => false,
                    'initialPreview' => $model->getEvidencias_preview(),
                    'initialPreviewAsData' => true,
                    'initialPreviewConfig' => $model->getEvidencias(),
                    'showPreview' => true,
                    'showCaption' => false,
                    'showRemove' => false,
                    'showUpload' => false,
                    'upload' => false,
                    'showBrowse' => false,
                    'showremoveClass' => false,
                    'showremoveIcon' => false,
                    'showZoom' => false,
                ]
            ]);
            Modal::end();
            ?>
            <?php
            $this->registerJs('
        $(\'.modal-lg\').css(\'width\', \'95%\');
        $(\'.btn-ajax-modal\').click(function (){
    var elm = $(this),
        target = elm.attr(\'data-target\'),
        ajax_body = elm.attr(\'value\');

    $(target).modal(\'show\')
        .find(\'.modal-content\')
        .load(ajax_body);
});');
            $this->registerJs('
$(".kv-file-remove").on("click", function() {
   
    $.ajax({
        type: "GET",
        data: {
            action: "deletefile",
            file: $(this).data("url"),
            id:' . $_GET['id'] . ',
            fileName: $(this).data("key"),
        },
        url: "index.php?r=subproyectos/delete-document",
        success: function(msg) {
        $(".kv-fileinput-error").hide();
        alert("Exito");
        self.parent.location.reload(); 
        },
    })
})
    ');
            ?>
</div>

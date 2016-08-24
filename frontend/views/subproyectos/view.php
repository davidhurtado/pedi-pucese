<?php

use yii\widgets\DetailView;
use kartik\file\FileInput;
use yii\widgets\ActiveForm;
use \app\models\Proyectos;
/* @var $this yii\web\View */
/* @var $model app\models\Subproyectos */
?>
<div class="subproyectos-view">

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'id_proyecto',
                'label'=>'Proyecto',
                'value' => Proyectos::findOne(['id' => $model->id_proyecto])->nombre,
            ],
            'fecha_inicio',
            'fecha_fin',
        ],
    ])
    ?>
    <div class="col-sm-12">
        <?php $form = ActiveForm::begin(); ?>
        <?=
        $form->field($model, 'evidencias[]')->widget(FileInput::classname(), [
            'options' => ['multiple' => true,
                'showRemove' => false,
                'showDelete' => false,
                'layoutTemplates' => [
                    'main2' => '<div class="kv-upload-progress hide"></div>{remove}{cancel}{upload}{preview}',
                ]
            ],
            'pluginOptions' => [
                'allowedFileExtensions' => ['pdf'],
                'initialPreview' => $model->getEvidencias_preview(),
                'initialPreviewAsData' => true,
                'initialPreviewConfig' => $model->getEvidencias(),
                'showPreview' => true,
                'showCaption' => false,
                'showRemove' => false,
                'showDelete' => false,
                'showUpload' => false,
                'upload' => false,
                'showBrowse' => false,
                'showRemoveClass' => false,
                'showRemoveIcon' => false,
                'showZoom' => false,
            ],
        ]);
        ?>

        <?php ActiveForm::end(); ?>
        <?php
        $this->registerJs('$(".kv-file-remove").hide();');
        ?>
    </div>

</div>

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
            [
                'label' => 'Estado',
                'value' => $model->estado == 1 ? 'Activado' : 'Desactivado',
            ],
        ],
    ])
    ?>

</div>

<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Subproyectos */
?>
<div class="subproyectos-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'id_proyecto',
            'nombre',
            'descripcion',
            'evidencias:ntext',
            'fecha_inicio',
            'fecha_fin',
        ],
    ]) ?>

</div>

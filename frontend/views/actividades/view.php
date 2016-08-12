<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Actividades */
?>
<div class="actividades-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'id_subproyecto',
            'descripcion',
            'codigo_presupuestario',
            'presupuesto',
            'fecha_inicio',
            'fecha_fin',
        ],
    ]) ?>

</div>

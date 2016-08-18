<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Estrategias */
?>
<div class="estrategias-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'id_objetivo',
            'descripcion',
            'responsables',
            'fecha_inicio',
            'fecha_fin',
            'presupuesto',
            'numeracion',
        ],
    ]) ?>

</div>

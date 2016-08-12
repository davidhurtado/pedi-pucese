<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Programas */
?>
<div class="programas-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'id_estrategia',
            'descripcion',
            'responsables',
            'fecha_inicio',
            'fecha_fin',
            'presupuesto',
        ],
    ]) ?>

</div>

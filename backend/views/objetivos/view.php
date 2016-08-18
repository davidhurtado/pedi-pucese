<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Objetivos */
?>
<div class="objetivos-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'descripcion',
            'responsables',
            'fecha_inicio',
            'fecha_fin',
            'numeracion',
        ],
    ]) ?>

</div>

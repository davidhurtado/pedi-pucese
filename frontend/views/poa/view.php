<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Poa */
?>
<div class="poa-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'fecha_creacion',
            'fecha_ejecucion',
            'fecha_fin',
            'estado',
        ],
    ]) ?>

</div>

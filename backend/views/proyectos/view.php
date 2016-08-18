<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Proyectos */
?>
<div class="proyectos-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'id_programa',
            'nombre',
            'descripcion',
            'responsables',
            'fecha_inicio',
            'fecha_fin',
            'presupuesto',
            'numeracion',
        ],
    ]) ?>

</div>

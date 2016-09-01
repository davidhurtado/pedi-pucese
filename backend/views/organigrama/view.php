<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Organigrama */
?>
<div class="organigrama-view">

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name:ntext',
            'created',
            [
                'label' => 'Estado',
                'value' => $model->activo == 1 ? 'Activado' : 'Desactivado',
            ],
        ],
    ])
    ?>

</div>

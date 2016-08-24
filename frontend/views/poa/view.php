<?php

use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model frontend\models\Poa */
?>
<div class="poa-view">

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'fecha_creacion',
            'fecha_ejecucion',
            'fecha_fin',
            'estado',
        ],
    ])
    ?>
    <?php
    if ($estado == 'aprobar') {
        ?>
        <div class="alert alert-danger"><strong>[AVISO]:</strong> Una vez aprobado el POA no se admite cambio alguno, y los proyectos que estan en borrador seran agregdos al 
            proximo POA, mientras estén revisados</div>
        <?php
        $form = ActiveForm::begin(['method'=>'post']);
        
        ActiveForm::end();
    }
    ?>

</div>

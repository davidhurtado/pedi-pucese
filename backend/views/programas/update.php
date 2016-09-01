<?php

use yii\helpers\Html;
use app\models\Estrategias;

/* @var $this yii\web\View */
/* @var $model app\models\Programas */
?>
<div class="programas-update">

    <?=
    $this->render('_form', [
        'model' => $model,
        'estrategia' => Estrategias::findOne($model->id_estrategia),
        'controlador' => $controlador,
        'accion' => $accion
    ])
    ?>
</div>

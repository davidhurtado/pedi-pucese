<?php

use yii\helpers\Html;
use app\models\Estrategias;

/* @var $this yii\web\View */
/* @var $model app\models\Programas */
?>
<div class="programas-create">
    <?php
    if ($controlador == 'estrategias') {
        $estrategia = Estrategias::findOne($id);
        echo $this->render('_form', [
            'model' => $model,
            'estrategia' => $estrategia,
            'controlador'=>$controlador,
            'accion' => $accion,
            'id' => $id
        ]);
    } else {
        $estrategia = Estrategias::find()->orderBy('id')->asArray()->all();
        echo $this->render('_form', [
            'model' => $estrategia,
            'controlador'=>$controlador,
            'accion' => $accion
        ]);
    }
    ?>
</div>
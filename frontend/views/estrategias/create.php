<?php

use yii\helpers\Html;
use app\models\Objetivos;

/* @var $this yii\web\View */
/* @var $model app\models\Estrategias */
?>
<div class="estrategias-create">
    <?php
    if ($controlador == 'objetivos') {
        $objetivo = Objetivos::findOne($id);
        echo $this->render('_form', [
            'model' => $model,
            'objetivo' => $objetivo,
            'controlador' => $controlador,
            'accion' => $accion,
            'id' => $id
        ]);
    } else {
        $objetivo = Objetivos::find()->where(['validacion'=>1])->orderBy('id')->asArray()->all();
        echo $this->render('_form', [
            'model' => $objetivo,
            'controlador' => $controlador,
            'accion' => $accion
        ]);
    }
    ?>
</div>
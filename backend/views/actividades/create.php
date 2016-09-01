<?php

use yii\helpers\Html;

use app\models\Proyectos;

/* @var $this yii\web\View */
/* @var $model app\models\Actividades */

?>
<div class="actividades-create">
      <?php
    if ($controlador == 'proyectos') {
        $proyecto = Proyectos::findOne($id);
        echo $this->render('_form', [
            'model' => $model,
            'proyecto' => $proyecto,
            'controlador'=>$controlador,
            'accion' => $accion,
            'id' => $id
        ]);
    } else {
        $proyecto= Programas::find()->orderBy('id')->asArray()->all();
        echo $this->render('_form', [
            'model' => $proyecto,
            'controlador'=>$controlador,
            'accion' => $accion
        ]);
    }
    ?>
</div>

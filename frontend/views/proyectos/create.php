<?php

use yii\helpers\Html;
use app\models\Programas;

/* @var $this yii\web\View */
/* @var $model app\models\Proyectos */

?>
<div class="proyectos-create">
     <?php
    if ($controlador == 'programas') {
        $programa = Programas::findOne($id);
        echo $this->render('_form', [
            'model' => $model,
            'programa' => $programa,
            'controlador'=>$controlador,
            'accion' => $accion,
            'id' => $id
        ]);
    } else {
        $programa = Programas::find()->orderBy('id')->asArray()->all();
        echo $this->render('_form', [
            'model' => $programa,
            'controlador'=>$controlador,
            'accion' => $accion
        ]);
    }
    ?>
</div>

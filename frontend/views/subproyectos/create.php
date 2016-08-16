<?php

use yii\helpers\Html;
use app\models\Proyectos;

/* @var $this yii\web\View */
/* @var $model app\models\Subproyectos */

?>
<div class="subproyectos-create">
    <?= $this->render('_form', [
        'model' => $model,
        'proyecto'=>  Proyectos::findOne($_GET["id"]),
    ]) ?>
</div>

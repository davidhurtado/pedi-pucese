<?php

use yii\helpers\Html;
use app\models\Proyectos;
/* @var $this yii\web\View */
/* @var $model app\models\Subproyectos */
?>
<div class="subproyectos-update">

    <?= $this->render('_form', [
        'model' => $model,
        'proyecto'=>  Proyectos::findOne($model->id_proyecto),
    ]) ?>

</div>

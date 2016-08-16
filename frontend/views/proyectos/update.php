<?php

use yii\helpers\Html;
use app\models\Programas;
/* @var $this yii\web\View */
/* @var $model app\models\Proyectos */
?>
<div class="proyectos-update">

    <?= $this->render('_form', [
        'model' => $model,
        'programa'=>  Programas::findOne($model->id_programa),
    ]) ?>

</div>

<?php

use yii\helpers\Html;
use app\models\Programas;

/* @var $this yii\web\View */
/* @var $model app\models\Proyectos */

?>
<div class="proyectos-create">
    <?= $this->render('_form', [
        'model' => $model,
        'programa'=>  Programas::findOne($_GET["id"]),
    ]) ?>
</div>

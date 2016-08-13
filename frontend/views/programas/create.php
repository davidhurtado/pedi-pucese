<?php

use yii\helpers\Html;
use app\models\Estrategias;

/* @var $this yii\web\View */
/* @var $model app\models\Programas */

?>
<div class="programas-create">
    <?= $this->render('_form', [
        'model' => $model,
        'fechas'=>  Estrategias::findOne($_GET["id"]),
    ]) ?>
</div>

<?php

use yii\helpers\Html;
use app\models\Objetivos;

/* @var $this yii\web\View */
/* @var $model app\models\Estrategias */

?>
<div class="estrategias-create">
    <?= $this->render('_form', [
        'model' => $model,
        'objetivo'=>  Objetivos::findOne($_GET["id"]),
    ]) ?>
</div>

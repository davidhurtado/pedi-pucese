<?php

use yii\helpers\Html;
use app\models\Objetivos;
/* @var $this yii\web\View */
/* @var $model app\models\Estrategias */
?>
<div class="estrategias-update">

    <?= $this->render('_form', [
        'model' => $model,
        'fechas'=>  Objetivos::findOne($model->id_objetivo),
    ]) ?>

</div>

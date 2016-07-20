<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Objetivos */

$this->title = 'Crear Objetivos';
$this->params['breadcrumbs'][] = ['label' => 'Objetivos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
    <div class="objetivos-create">

        <h3><?= Html::encode($this->title) ?></h3>

        <?=
        $this->render('_form', [
            'model' => $model,
        ])
        ?>

    </div>
</div>

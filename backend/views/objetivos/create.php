<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Objetivos */

$this->title = 'Create Objetivos';
$this->params['breadcrumbs'][] = ['label' => 'Objetivos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="objetivos-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

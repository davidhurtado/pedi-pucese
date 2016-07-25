<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Programas */

$this->title = 'Create Programas';
$this->params['breadcrumbs'][] = ['label' => 'Programas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
    <div class="programas-create">

        <h1><?= Html::encode($this->title) ?></h1>

        <?=
        $this->render('_form', [
            'model' => $model,
        ])
        ?>

    </div>
</div>

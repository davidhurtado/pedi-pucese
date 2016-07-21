<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Estrategias */

$this->title = 'Crear Estrategias';
$this->params['breadcrumbs'][] = ['label' => 'Estrategias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="estrategias-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'evidencias_preview'=>'',
        'evidencias'=>'',
    ]) ?>

</div>

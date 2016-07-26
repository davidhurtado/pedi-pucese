<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Subproyectos */

$this->title = 'Create Subproyectos';
$this->params['breadcrumbs'][] = ['label' => 'Subproyectos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
    <div class="subproyectos-create">

        <h1><?= Html::encode($this->title) ?></h1>

        <?=
        $this->render('_form', [
            'model' => $model,
            'evidencias_preview'=>'',
        'evidencias'=>'',
        ])
        ?>

    </div>
</div>
<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Subproyectos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subproyectos-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'id_proyecto',
            'nombre',
            'descripcion',
            'evidencias_subproyectos',
            // 'fecha_inicio',
            // 'fecha_fin',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

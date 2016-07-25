<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Programas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="programas-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'id_estrategia',
            'descripcion',
            'responsables',
            'fecha_inicio',
            // 'fecha_fin',
            // 'presupuesto',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

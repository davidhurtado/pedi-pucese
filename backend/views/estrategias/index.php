<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Estrategias';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="estrategias-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Estrategias', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'id_objetivo',
            'descripcion',
            'responsables',
            'fecha_inicio',
            // 'fecha_fin',
            // 'evidencias',
            // 'presupuesto',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

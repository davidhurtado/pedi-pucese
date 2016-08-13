<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Organigramas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="organigrama-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Crear Organigrama', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary'=>'',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name:ntext',
            'created',
            ['attribute' => 'activo',
                                'value' => function($data) {
                                    return $data['activo'] == 1 ?  'Activado':'Desactivado';
                                }],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

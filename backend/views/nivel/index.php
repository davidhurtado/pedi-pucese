<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Organigrama;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Niveles';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="niveles-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Crear nivel', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'nid',
            'title:ntext',
            'rid',
            ['attribute' => 'org_id',
                'value' => function($data) {
                    return Organigrama::findOne(['id' => $data['org_id']])->name;
                }],
                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]);
            ?>
</div>

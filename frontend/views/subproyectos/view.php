<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $model app\models\Subproyectos */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Subproyectos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subproyectos-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'nombre',
            'descripcion',
            'evidencias_subproyectos',
            'fecha_inicio',
            'fecha_fin',
        ],
    ]) ?>
<h3>ACTIVIDADES</h3>
  <?= Html::a('Crear Actividades',  Url::to(['/actividades/index', 'pid' => $model->id]), ['class' => 'btn btn-success']) ?>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            //'id',
            ['class' => 'yii\grid\SerialColumn'],
            'descripcion',
            'codigo_presupuestario',
            //'fecha_inicio',
            //'fecha_fin',
            // 'evidencias',
             'presupuesto',
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view}{update}{delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['actividades/view', 'id' => $model['id']], [
                                    'title' => Yii::t('yii', 'Ver'),
                        ]);
                    },
                            'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['actividades/update', 'id' => $model['id']], [
                                    'title' => Yii::t('yii', 'Modificar'),
                        ]);
                    },
                            'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['actividades/delete', 'id' => $model['id']], [
                                    'title' => Yii::t('yii', 'Eliminar'),
                        ]);
                    },
                        ]
                    ],
                ],
            ]);
            ?>
</div>

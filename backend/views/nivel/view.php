<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Niveles */
?>
<div class="niveles-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'nid',
            'title:ntext',
            'rid',
            'org_id',
        ],
    ]) ?>

</div>

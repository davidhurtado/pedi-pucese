<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
$this->title = 'PEDI PUCESE 2016';
?>
<div class="site-index">

    <div class="jumbotron">
        <h2 class="bienvenida">Bienvenido al Sistema PEDI</h2>

        <p class="lead">Planifica de manera eficiente</p>

        <p><?=Html::a('<i class="glyphicon glyphicon-plus"></i> NUEVO PROYECTO', ['proyectos/index'], ['class' => 'btn btn-lg boton'])
                    ?></p>
    </div>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Estrategias */

$this->title = 'Actualizar Estrategias: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Estrategias', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="estrategias-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'evidencias_preview' => $model->getEvidencias_preview(),
        'evidencias' => $model->getEvidencias(),
    ])
    ?>
    <?php
    $this->registerJs('
$(".kv-file-remove").on("click", function() {
   
    $.ajax({
        type: "GET",
        data: {
            action: "deletefile",
            file: $(this).data("url"),
            id:' . $_GET['id'] . ',
            fileName: $(this).data("key"),
        },
        url: "index.php?r=estrategias/delete-document",
        success: function(msg) {
        $(".kv-fileinput-error").hide();
        alert("Exito");
        self.parent.location.reload(); 
        },
    })
})
    ');
    ?>
</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Subproyectos */

$this->title = 'Update Subproyectos: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Subproyectos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="subproyectos-update">

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
        url: "index.php?r=subproyectos/delete-document",
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

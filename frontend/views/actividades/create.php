<?php

use yii\helpers\Html;
use app\models\Proyectos;
use app\models\Subproyectos;
/* @var $this yii\web\View */
/* @var $model app\models\Actividades */
?>
<div class="actividades-create">
<?php
if ($controlador == 'proyectos') {
    $proyecto = Proyectos::findOne($id);
    $subproyecto = Subproyectos::find()->where(['id_proyecto' => $proyecto->id])->andWhere(['estado' => 3])->one();
    echo $this->render('_form', [
        'model' => $model,
        'proyecto' => $proyecto,
        'controlador' => $controlador,
        'accion' => $accion,
        'id' => $id,
        'subproyecto' => $subproyecto
    ]);
} else {
    $proyecto = Proyectos::find()->where(['validacion' => 1])->andWhere(['estado' => 3])->andWhere(['responsable' => Yii::$app->user->identity->id])->orderBy('id')->asArray()->all();
    echo $this->render('_form', [
        'model' => $proyecto,
        'controlador' => $controlador,
        'accion' => $accion
    ]);
}
?>
</div>

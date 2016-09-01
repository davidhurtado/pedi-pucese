<?php

namespace app\models;

use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use yii\helpers\Url;
use Yii;
use \yii\db\Query;
/**
 * This is the model class for table "subproyectos".
 *
 * @property integer $id
 * @property integer $id_proyecto
 * @property string $nombre
 * @property string $descripcion
 * @property string $evidencias_subproyectos
 * @property string $fecha_inicio
 * @property string $fecha_fin
 *
 * @property Actividades[] $actividades
 * @property Proyectos $idProyecto
 */
class Subproyectos extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public $evidencias;
    public $evidencias_array = Array();

    public static function tableName() {
        return 'subproyectos';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id_proyecto', 'fecha_inicio', 'fecha_fin'], 'required'],
            [['id_proyecto'], 'integer'],
            //[['evidencias'], 'string', 'max' => 300],
            [['id_proyecto'], 'exist', 'skipOnError' => true, 'targetClass' => Proyectos::className(), 'targetAttribute' => ['id_proyecto' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'id_proyecto' => 'Id Proyecto',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_fin' => 'Fecha Fin',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActividades() {
        return $this->hasMany(Actividades::className(), ['id_subproyecto' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdProyecto() {
        return $this->hasOne(Proyectos::className(), ['id' => 'id_proyecto']);
    }

    public function getFechas() {
        $model_ = Subproyectos::findOne($_GET['id']);
        return $model_;
    }


    //Para los Fixtures
    public function saveSubproyecto() {
        if (!$this->validate()) {
            return null;
        }
        $subproyecto = new Subproyectos();
        $subproyecto->id_proyecto = $this->id_proyecto;
        $subproyecto->evidencias = $this->evidencias;
        $subproyecto->fecha_inicio = $this->fecha_inicio;
        $subproyecto->fecha_fin = $this->fecha_fin;
        return $subproyecto->save() ? $subproyecto : null;
    }

}

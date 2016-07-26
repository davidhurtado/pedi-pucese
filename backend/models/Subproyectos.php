<?php

namespace app\models;

use Yii;

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
class Subproyectos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'subproyectos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_proyecto', 'nombre', 'descripcion', 'evidencias', 'fecha_inicio', 'fecha_fin'], 'required'],
            [['id_proyecto'], 'integer'],
            [['fecha_inicio', 'fecha_fin'], 'safe'],
            [['nombre'], 'string', 'max' => 200],
            [['descripcion'], 'string', 'max' => 500],
            [['evidencias'], 'string', 'max' => 300],
            [['id_proyecto'], 'exist', 'skipOnError' => true, 'targetClass' => Proyectos::className(), 'targetAttribute' => ['id_proyecto' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_proyecto' => 'Id Proyecto',
            'nombre' => 'Nombre',
            'descripcion' => 'Descripcion',
            'evidencias' => 'Evidencias Subproyectos',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_fin' => 'Fecha Fin',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActividades()
    {
        return $this->hasMany(Actividades::className(), ['id_subproyecto' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdProyecto()
    {
        return $this->hasOne(Proyectos::className(), ['id' => 'id_proyecto']);
    }
}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "objetivos".
 *
 * @property integer $id
 * @property string $descripcion
 * @property string $responsables
 * @property string $fecha_inicio
 * @property string $fecha_fin
 * @property string $evidencias
 *
 * @property Estrategias[] $estrategias
 */
class Objetivos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'objetivos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['descripcion', 'responsables', 'fecha_inicio', 'fecha_fin', 'evidencias'], 'required'],
            [['fecha_inicio', 'fecha_fin'], 'safe'],
            [['descripcion'], 'string', 'max' => 500],
            [['responsables'], 'string', 'max' => 100],
            [['evidencias'], 'string', 'max' => 300],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'descripcion' => 'Descripcion',
            'responsables' => 'Responsables',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_fin' => 'Fecha Fin',
            'evidencias' => 'Evidencias',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEstrategias()
    {
        return $this->hasMany(Estrategias::className(), ['id_objetivo' => 'id']);
    }
}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "objetivos".
 *
 * @property integer $id
 * @property string $descripcion
 * @property string $colaboradores
 * @property string $fecha_inicio
 * @property string $fecha_fin
 * @property integer $numeracion
 * @property integer $responsable
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
            [['descripcion', 'colaboradores', 'fecha_inicio', 'fecha_fin', 'responsable'], 'required'],
            [['fecha_inicio', 'fecha_fin'], 'safe'],
            [['numeracion', 'responsable'], 'integer'],
            [['descripcion'], 'string', 'max' => 500],
            [['colaboradores'], 'string', 'max' => 100],
            [['numeracion'], 'unique'],
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
            'colaboradores' => 'Colaboradores',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_fin' => 'Fecha Fin',
            'numeracion' => 'Numeracion',
            'responsable' => 'Responsable',
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

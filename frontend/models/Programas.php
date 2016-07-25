<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "programas".
 *
 * @property integer $id
 * @property integer $id_estrategia
 * @property string $descripcion
 * @property string $responsables
 * @property string $fecha_inicio
 * @property string $fecha_fin
 * @property string $presupuesto
 *
 * @property Estrategias $idEstrategia
 * @property Proyectos[] $proyectos
 */
class Programas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'programas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['descripcion', 'responsables', 'fecha_inicio', 'fecha_fin'], 'required'],
            [['id_estrategia'], 'integer'],
            [['fecha_inicio', 'fecha_fin'], 'safe'],
            [['presupuesto'], 'number'],
            [['descripcion'], 'string', 'max' => 500],
            [['responsables'], 'string', 'max' => 100],
            [['id_estrategia'], 'exist', 'skipOnError' => true, 'targetClass' => Estrategias::className(), 'targetAttribute' => ['id_estrategia' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_estrategia' => 'Id Estrategia',
            'descripcion' => 'Descripcion',
            'responsables' => 'Responsables',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_fin' => 'Fecha Fin',
            'presupuesto' => 'Presupuesto',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdEstrategia()
    {
        return $this->hasOne(Estrategias::className(), ['id' => 'id_estrategia']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProyectos()
    {
        return $this->hasMany(Proyectos::className(), ['id_programa' => 'id']);
    }
}

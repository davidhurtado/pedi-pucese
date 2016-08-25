<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "poa".
 *
 * @property integer $id
 * @property string $fecha_creacion
 * @property string $fecha_ejecucion
 * @property string $fecha_fin
 * @property integer $estado
 *
 * @property PoaProyectos[] $poaProyectos
 * @property Proyectos[] $idProyectos
 */
class Poa extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'poa';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fecha_ejecucion'], 'required'],
            [['fecha_creacion', 'fecha_ejecucion'], 'safe'],
            [['fecha_ejecucion'], 'unique'],
            [['estado'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fecha_creacion' => 'Fecha Creacion',
            'fecha_ejecucion' => 'Fecha Ejecucion',
            'estado' => 'Estado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPoaProyectos()
    {
        return $this->hasMany(PoaProyectos::className(), ['id_poa' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdProyectos()
    {
        return $this->hasMany(Proyectos::className(), ['id' => 'id_proyecto'])->viaTable('poa_proyectos', ['id_poa' => 'id']);
    }
}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "actividades".
 *
 * @property integer $id
 * @property integer $id_subproyecto
 * @property string $descripcion
 * @property string $codigo_presupuestario
 * @property string $presupuesto
 * @property string $fecha_inicio
 * @property string $fecha_fin
 * @property integer $validacion
 *
 * @property Subproyectos $idSubproyecto
 */
class Actividades extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'actividades';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_subproyecto', 'descripcion', 'codigo_presupuestario', 'fecha_inicio', 'fecha_fin'], 'required'],
            [['id_subproyecto', 'validacion'], 'integer'],
            [['presupuesto'], 'number'],
            [['fecha_inicio', 'fecha_fin'], 'safe'],
            [['descripcion'], 'string', 'max' => 500],
            [['codigo_presupuestario'], 'string', 'max' => 10],
            [['id_subproyecto'], 'exist', 'skipOnError' => true, 'targetClass' => Subproyectos::className(), 'targetAttribute' => ['id_subproyecto' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_subproyecto' => 'Id Subproyecto',
            'descripcion' => 'Descripcion',
            'codigo_presupuestario' => 'Codigo Presupuestario',
            'presupuesto' => 'Presupuesto',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_fin' => 'Fecha Fin',
            'validacion' => 'Validacion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdSubproyecto()
    {
        return $this->hasOne(Subproyectos::className(), ['id' => 'id_subproyecto']);
    }
}

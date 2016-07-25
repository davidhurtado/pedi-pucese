<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "proyectos".
 *
 * @property integer $id
 * @property integer $id_programa
 * @property string $nombre
 * @property string $descripcion
 * @property string $responsable
 * @property string $fecha_inicio
 * @property string $fecha_fin
 * @property string $presupuesto
 *
 * @property Programas $idPrograma
 * @property Subproyectos[] $subproyectos
 */
class Proyectos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'proyectos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_programa', 'nombre', 'descripcion', 'responsable', 'fecha_inicio', 'fecha_fin'], 'required'],
            [['id_programa'], 'integer'],
            [['fecha_inicio', 'fecha_fin'], 'verifDate'],
            [['presupuesto'], 'number'],
            [['nombre'], 'string', 'max' => 200],
            [['descripcion'], 'string', 'max' => 500],
            [['responsable'], 'string', 'max' => 100],
            [['id_programa'], 'exist', 'skipOnError' => true, 'targetClass' => Programas::className(), 'targetAttribute' => ['id_programa' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_programa' => 'Id Programa',
            'nombre' => 'Nombre',
            'descripcion' => 'Descripcion',
            'responsable' => 'Responsable',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_fin' => 'Fecha Fin',
            'presupuesto' => 'Presupuesto',
        ];
    }
    //  -----> CREAR REGLAS DE VALIDACIONES PARA FECHAS    
    public function verifDate($attribute) {
        $time = new \DateTime('now', new \DateTimeZone('America/Guayaquil'));
        $currentDate = $time->format('Y-m-d h:m:s');
           
        if ($this->$attribute <= $currentDate) {
            $this->addError($attribute, 'No puede ser menor a la fecha actual');
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdPrograma()
    {
        return $this->hasOne(Programas::className(), ['id' => 'id_programa']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubproyectos()
    {
        return $this->hasMany(Subproyectos::className(), ['id_proyecto' => 'id']);
    }
}

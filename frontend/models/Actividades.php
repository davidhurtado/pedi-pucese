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
 * @property string $presupuesto_actividades
 * @property string $fecha_inicio
 * @property string $fecha_fin
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
            [['descripcion', 'codigo_presupuestario','fecha_inicio','fecha_fin'], 'required'],
            [['id_subproyecto'], 'integer'],
            [['presupuesto'], 'number'],
            [['fecha_inicio','fecha_fin'], 'verifDate'],
            [['descripcion'], 'string', 'max' => 500],
            [['codigo_presupuestario'], 'string', 'max' => 10],
            [['id_subproyecto'], 'exist', 'skipOnError' => true, 'targetClass' => Subproyectos::className(), 'targetAttribute' => ['id_subproyecto' => 'id']],
        ];
    }
    

     //  -----> CREAR REGLAS DE VALIDACIONES PARA FECHAS    
    public function verifDate($attribute){
        $time = new \DateTime('now', new \DateTimeZone('America/Guayaquil'));
        $currentDate = $time->format('Y-m-d h:m:s');
        
        if($this->$attribute <=  $currentDate ){
            $this->addError($attribute, 'No puede ser menor a la fecha actual');
        }
        
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
            'presupuesto_actividades' => 'Presupuesto Actividades',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_fin' => 'Fecha Fin',
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

<?php

namespace app\models;

use yii\helpers\Html;
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
            [['nombre', 'descripcion', 'responsables', 'fecha_inicio', 'fecha_fin', 'p_status'], 'required'],
            [['id_programa'], 'integer'],
            [['fecha_inicio', 'fecha_fin'], 'verifDate'],
            [['presupuesto'], 'number'],
            [['p_status'], 'integer', 'max' => 3 ],
            [['nombre'], 'string', 'max' => 200],
            [['descripcion'], 'string', 'max' => 500],
            [['responsables'], 'string', 'max' => 100],
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
            'responsables' => 'Responsables',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_fin' => 'Fecha Fin',
            'p_status' => 'Estado',
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
     public function getLevels() {


        $query = new \yii\db\Query();
        $query->select(['niveles.*', 'title', 'nid', 'org_id'])
                ->from('niveles')->where(['rid' => 2])
                ->orderBy(['title' => SORT_DESC]);

        $cmd = $query->createCommand();
        $levels = $cmd->queryAll();

        return $levels;
    }

    public function getResponsables($resp = 1 ) {

    	
    }

    // --> Truncar descripcion a x chars
    public function truncDesc(){
    	$tooltip_desc = Html::tag('span', '...', [
		    'title'=> $this->descripcion,
		    'data-toggle'=>'tooltip',
		    'style'=>'text-decoration: underline; cursor:pointer;'
		]);
    	return (strlen(trim($this->descripcion, ' ')) > 20 ? substr($this->descripcion, 0, 20).$tooltip_desc : $this->descripcion);
    }
    public function listSubProjects(){
    	$ret = '';

    	$query = new \yii\db\Query();
        $query->select(['subproyectos.*', 'nombre', 'descripcion', 'id'])
                ->from('subproyectos')->where(['id_proyecto' => $this->id])
                ->orderBy(['nombre' => SORT_ASC]);

        $cmd = $query->createCommand();
        $subprojects = $cmd->queryAll();

        //print_r($subprojects[0]);

        //$subprojects = $subprojects[0];
        //die;

    	if(sizeof($subprojects) > 0 ){
    		foreach ($subprojects as $key => $subpr ) {
    			$ret .= $subpr['nombre']."\n";
    		}
    	}else{
    		$ret .= 'Sin subproyectos...';
    	}

    	return $ret;
    }
}

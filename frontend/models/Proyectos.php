<?php

namespace app\models;

use Yii;
use \yii\db\Query;
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
class Proyectos extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'proyectos';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['nombre', 'descripcion', 'colaboradores', 'fecha_inicio', 'fecha_fin', 'numeracion'], 'required'],
            [['numeracion', 'estado'], 'integer'],
            [['numeracion'], 'VerifNum'],
            [['estado'], 'VerifCheck'],
            [['id_programa'], 'integer'],
            [['fecha_inicio'], 'verifDate_inicio'],
            [['fecha_fin'], 'verifDate_fin'],
            [['presupuesto'], 'number'],
            //[['nombre'], 'string', 'max' => 200],
            [['descripcion'], 'string'],
            [['colaboradores'], 'validarColaboradores'],
            [['id_programa'], 'exist', 'skipOnError' => true, 'targetClass' => Programas::className(), 'targetAttribute' => ['id_programa' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'id_programa' => 'Id Programa',
            'nombre' => 'Nombre',
            'descripcion' => 'Descripción',
            'colaboradores' => 'Responsable',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_fin' => 'Fecha Fin',
            'presupuesto' => 'Presupuesto',
            'numeracion' => 'Numeración'
        ];
    }
    //  -----> CREAR REGLAS DE VALIDACIONES PARA NUMERACION   
    public function verifNum($attribute) {
        $query = new Query();
        $query->select('*')->from('proyectos')->where(['id_programa' => $this->id_programa])->andWhere(['numeracion' => $this->$attribute]);
        $cmd = $query->createCommand()->queryOne();
        $query1 = new Query();
        $query1->select('*')->from('proyectos')->where(['id_programa' => $this->id_programa])->andWhere(['id' => $this->id]);
        $cmd1 = $query1->createCommand()->queryOne();
        if (isset($cmd['numeracion'])) {
            if ($this->$attribute != $cmd1['numeracion']) {
                $this->addError($attribute, 'Numeracion "'. $this->$attribute . '" ya ha sido utilizado.');
            }
        }
    }
    //  -----> CREAR REGLAS DE VALIDACIONES PARA NUMERACION   
    public function verifCheck($attribute) {
        
            if ($this->$attribute <0 and $this->$attribute >4) {
                $this->addError($attribute, 'No existe ese estado');
            }
    }
    //  -----> CREAR REGLAS DE VALIDACIONES PARA FECHAS    
    public function verifDate_inicio($attribute) {
        if ($this->$attribute < Programas::findOne($this->id_programa)->fecha_inicio) {
            $this->addError($attribute, 'No puede ser menor a la fecha inicial del programa');
        }
    }

    public function verifDate_fin($attribute) {
        if ($this->$attribute > Programas::findOne($this->id_programa)->fecha_fin) {
            $this->addError($attribute, 'No puede ser mayor a la fecha final del programa');
        }
    }
    //  -----> CREAR REGLAS DE VALIDACIONES PARA RESPONSABLES   
    public function validarColaboradores($attribute) {
        if (empty($this->$attribute)) {
            $this->addError($attribute, 'No existe ningun responsable');
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdPrograma() {
        return $this->hasOne(Programas::className(), ['id' => 'id_programa']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubproyectos() {
        return $this->hasMany(Subproyectos::className(), ['id_proyecto' => 'id']);
    }

    public function getLevels() {
        $query = new Query();
        $query_org = new Query();
        $query_org->select(['id'])
                ->from('organigrama')->where(['activo' => 1]);
        $organigrama = $query_org->createCommand()->queryOne();
        $query->select(['niveles.*', 'title', 'nid', 'org_id'])
                ->from('niveles')->where(['org_id' => $organigrama['id']])
                ->orderBy(['title' => SORT_DESC]);

        $cmd = $query->createCommand();
        $levels = $cmd->queryAll();

        return $levels;
    }

    public function getColaboradores($resp) {


        $query = new \yii\db\Query();
        $query->select(['niveles.*', 'title', 'org_id'])
                ->from('niveles')->where(['nid' => $resp]);

        $cmd = $query->createCommand();
        $levels = $cmd->queryAll();
        $textResp = '';
        foreach ($levels as $responsable):
            $textResp.="(" . $responsable['title'] . ") ";
        endforeach;
        return $textResp;
    }

    //Para los Fixtures
    public function saveProyecto() {
        if (!$this->validate()) {
            return null;
        }
        $proyecto = new Proyectos();
        $proyecto->id_programa = $this->id_programa;
        $proyecto->nombre = $this->nombre;
        $proyecto->descripcion = $this->descripcion;
        $proyecto->colaboradores = $this->colaboradores;
        $proyecto->fecha_inicio = $this->fecha_inicio;
        $proyecto->fecha_fin = $this->fecha_fin;
        $proyecto->presupuesto = $this->presupuesto;
        return $proyecto->save() ? $proyecto : null;
    }

}

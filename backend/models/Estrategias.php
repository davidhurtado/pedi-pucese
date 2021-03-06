<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use \yii\db\Query;

/**
 * This is the model class for table "estrategias".
 *
 * @property integer $id
 * @property integer $id_objetivo
 * @property string $descripcion
 * @property string $colaboradores
 * @property string $fecha_inicio
 * @property string $fecha_fin
 * @property string $evidencias
 * @property string $presupuesto
 *
 * @property Objetivos $idObjetivo
 * @property Programas[] $programas
 */
class Estrategias extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public $evidencias;
    public $evidencias_array = Array();

    public static function tableName() {
        return 'estrategias';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id_objetivo', 'descripcion', 'colaboradores', 'fecha_inicio', 'fecha_fin', 'numeracion'], 'required'],
            [['numeracion'], 'integer'],
            [['numeracion'], 'VerifNum'],
            [['id_objetivo'], 'integer'],
            [['fecha_inicio'], 'verifDate_inicio'],
            [['fecha_fin'], 'verifDate_fin'],
            [['presupuesto'], 'number'],
            [['descripcion'], 'string'],
            [['validacion'], 'integer'],
            [['colaboradores'], 'validarColaboradores'],
            [['id_objetivo'], 'exist', 'skipOnError' => true, 'targetClass' => Objetivos::className(), 'targetAttribute' => ['id_objetivo' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'id_objetivo' => 'Id Objetivo',
            'descripcion' => 'Descripción',
            'colaboradores' => 'Colaboradores',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_fin' => 'Fecha Fin',
            'presupuesto' => 'Presupuesto',
            'validacion' => 'Validacion',
            'numeracion' => 'Numeración'
        ];
    }

    //  -----> CREAR REGLAS DE VALIDACIONES PARA NUMERACION   
    public function verifNum($attribute) {
        $query = new Query();
        $query->select('*')->from('estrategias')->where(['id_objetivo' => $this->id_objetivo])->andWhere(['numeracion' => $this->$attribute]);
        $cmd = $query->createCommand()->queryOne();
        $query1 = new Query();
        $query1->select('*')->from('estrategias')->where(['id_objetivo' => $this->id_objetivo])->andWhere(['id' => $this->id]);
        $cmd1 = $query1->createCommand()->queryOne();
        if (isset($cmd['numeracion'])) {
            if ($this->$attribute != $cmd1['numeracion']) {
                $this->addError($attribute, 'Numeracion "'. $this->$attribute . '" ya ha sido utilizado.');
            }
        }
    }

    //  -----> CREAR REGLAS DE VALIDACIONES PARA FECHAS    
    public function verifDate_inicio($attribute) {
        if ($this->$attribute < Objetivos::findOne($this->id_objetivo)->fecha_inicio) {
            $this->addError($attribute, 'No puede ser menor a la fecha inicial del objetivo');
        }
    }

    public function verifDate_fin($attribute) {
        if ($this->$attribute > Objetivos::findOne($this->id_objetivo)->fecha_fin) {
            $this->addError($attribute, 'No puede ser mayor a la fecha final del objetivo');
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
    public function getIdObjetivo() {
        return $this->hasOne(Objetivos::className(), ['id' => 'id_objetivo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFechas() {
        $model_ = Objetivos::findOne($_GET['id']);
        return $model_;
    }

    public function getObjetivo($id) {
        $modelObjetivo = Objetivos::findOne($id)->descripcion;
        return $modelObjetivo;
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


        $query = new Query();
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
    public function saveEstrategia() {
        if (!$this->validate()) {
            return null;
        }
        $estrategia = new Estrategias();
        $estrategia->id_objetivo = $this->id_objetivo;
        $estrategia->descripcion = $this->descripcion;
        $estrategia->colaboradores = $this->colaboradores;
        $estrategia->fecha_inicio = $this->fecha_inicio;
        $estrategia->fecha_fin = $this->fecha_fin;
        $estrategia->validacion = $this->validacion;
        $estrategia->presupuesto = $this->presupuesto;
        return $estrategia->save() ? $estrategia : null;
    }

}

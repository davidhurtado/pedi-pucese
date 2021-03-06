<?php

namespace app\models;

use Yii;
use \yii\db\Query;

/**
 * This is the model class for table "programas".
 *
 * @property integer $id
 * @property integer $id_estrategia
 * @property string $descripcion
 * @property string $colaboradores
 * @property string $fecha_inicio
 * @property string $fecha_fin
 * @property string $presupuesto
 *
 * @property Estrategias $idEstrategia
 * @property Proyectos[] $proyectos
 */
class Programas extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'programas';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['descripcion', 'colaboradores', 'fecha_inicio', 'fecha_fin', 'numeracion'], 'required'],
            [['numeracion','validacion'], 'integer'],
            [['numeracion'], 'VerifNum'],
            [['id_estrategia'], 'integer'],
            [['fecha_inicio'], 'verifDate_inicio'],
            [['fecha_fin'], 'verifDate_fin'],
            [['presupuesto'], 'number'],
            [['descripcion'], 'string'],
            [['colaboradores'], 'validarColaboradores'],
            [['id_estrategia'], 'exist', 'skipOnError' => true, 'targetClass' => Estrategias::className(), 'targetAttribute' => ['id_estrategia' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'id_estrategia' => 'Id Estrategia',
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
        $query->select('*')->from('programas')->where(['id_estrategia' => $this->id_estrategia])->andWhere(['numeracion' => $this->$attribute]);
        $cmd = $query->createCommand()->queryOne();
        $query1 = new Query();
        $query1->select('*')->from('programas')->where(['id_estrategia' => $this->id_estrategia])->andWhere(['id' => $this->id]);
        $cmd1 = $query1->createCommand()->queryOne();
        if (isset($cmd['numeracion'])) {
            if ($this->$attribute != $cmd1['numeracion']) {
                $this->addError($attribute, 'Numeracion "' . $this->$attribute . '" ya ha sido utilizado.');
            }
        }
    }

    //  -----> CREAR REGLAS DE VALIDACIONES PARA FECHAS    
    public function verifDate_inicio($attribute) {
        if ($this->$attribute < Estrategias::findOne($this->id_estrategia)->fecha_inicio) {
            $this->addError($attribute, 'No puede ser menor a la fecha inicial de la estrategia');
        }
    }

    public function verifDate_fin($attribute) {
        if ($this->$attribute > Estrategias::findOne($this->id_estrategia)->fecha_fin) {
            $this->addError($attribute, 'No puede ser mayor a la fecha final de la estrategia');
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
    public function getIdEstrategia() {
        return $this->hasOne(Estrategias::className(), ['id' => 'id_estrategia']);
    }

    public function getIdObjetivo() {
        $model = $this->hasOne(Estrategias::className(), ['id' => 'id_estrategia']);
        return $this->hasOne(Objetivos::className(), ['id' => $model->id]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProyectos() {
        return $this->hasMany(Proyectos::className(), ['id_programa' => 'id']);
    }

    public function getFechas() {
        $model_ = Estrategias::findOne($_GET['id']);
        return $model_;
    }

    public function getNumero($id) {
        $query = new Query();
        $query->select('*')->from('numeracion_programas')->where(['id_programa' => $id]);
        $numeracion = $query->createCommand()->queryOne();
        return $numeracion;
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
    public function savePrograma() {
        if (!$this->validate()) {
            return null;
        }
        $programa = new Programas();
        $programa->id_estrategia = $this->id_estrategia;
        $programa->descripcion = $this->descripcion;
        $programa->colaboradores = $this->colaboradores;
        $programa->fecha_inicio = $this->fecha_inicio;
        $programa->fecha_fin = $this->fecha_fin;
        $programa->validacion = $this->validacion;
        $programa->presupuesto = $this->presupuesto;
        return $programa->save() ? $programa : null;
    }

}

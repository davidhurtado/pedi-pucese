<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use dektrium\rbac\components\DbManager;
use \yii\db\Query;

/**
 * This is the model class for table "objetivos".
 *
 * @property integer $id
 * @property string $descripcion
 * @property string $colaboradores
 * @property string $fecha_inicio
 * @property string $fecha_fin
 *
 * @property Estrategias[] $estrategias
 */
class Objetivos extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public $evidencias;
    public $evidencias_array = Array();

    public static function tableName() {
        return 'objetivos';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['descripcion', 'colaboradores','validacion', 'fecha_inicio','fecha_fin','numeracion'], 'required'],
            [['numeracion','validacion'], 'integer'],
            [['numeracion'], 'unique'],
            [['fecha_inicio', 'fecha_fin'], 'verifDate'],
            [['descripcion'], 'string'],
            [['colaboradores'], 'validarColaboradores'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'descripcion' => 'Descripción',
            'colaboradores' => 'Colaboradores',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_fin' => 'Fecha Fin',
            'validacion'=>'Validación',
            'numeracion' => 'Numeración',
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

//  -----> CREAR REGLAS DE VALIDACIONES PARA RESPONSABLES   
    public function validarColaboradores($attribute) {
        if (empty($this->$attribute)) {
            $this->addError($attribute, 'No existe ningun responsable');
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEstrategias() {
        return $this->hasMany(Estrategias::className(), ['id_objetivo' => 'id']);
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
    public function saveObjetivo() {
        if (!$this->validate()) {
            return null;
        }
        $objetivo = new Objetivos();
        $objetivo->descripcion = $this->descripcion;
        $objetivo->colaboradores = $this->colaboradores;
         $objetivo->validacion = $this->validacion;
        $objetivo->fecha_inicio = $this->fecha_inicio;
        $objetivo->fecha_fin = $this->fecha_fin;
        return $objetivo->save() ? $objetivo : null;
    }

}

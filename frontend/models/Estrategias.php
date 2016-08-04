<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use yii\helpers\Url;

/**
 * This is the model class for table "estrategias".
 *
 * @property integer $id
 * @property integer $id_objetivo
 * @property string $descripcion
 * @property string $responsables
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
            [['id_objetivo', 'descripcion', 'fecha_inicio', 'fecha_fin'], 'required'],
            [['id_objetivo'], 'integer'],
            [['fecha_inicio', 'fecha_fin'], 'verifDate'],
            [['presupuesto'], 'number'],
            [['descripcion'], 'string', 'max' => 500],
            [['responsables'], 'string', 'max' => 100],
            //[['evidencias'], 'string', 'max' => 600],
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
            'descripcion' => 'Descripcion',
            'responsables' => 'Responsables',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_fin' => 'Fecha Fin',
            'evidencias' => 'Evidencias',
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
    public function getIdObjetivo() {
        return $this->hasOne(Objetivos::className(), ['id' => 'id_objetivo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */

    public function getFechaObjetivo() {
        $modelObjetivo = Objetivos::findOne($_GET['id']);
        return $modelObjetivo;
    }

    public function getObjetivo($id) {
        $modelObjetivo = Objetivos::findOne($id)->descripcion;
        return $modelObjetivo;
    }

    public function getLevels() {


        $query = new \yii\db\Query();
        $query->select(['niveles.*', 'title', 'nid', 'org_id'])
                ->from('niveles')//->where(['rid' => 7])
                ->orderBy(['title' => SORT_DESC]);

        $cmd = $query->createCommand();
        $levels = $cmd->queryAll();

        return $levels;
    }

    public function getResponsables($resp) {


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
    public function saveEstrategia() {
        if (!$this->validate()) {
            return null;
        }
        $estrategia = new Estrategias();
        $estrategia->id_objetivo = $this->id_objetivo;
        $estrategia->descripcion = $this->descripcion;
        $estrategia->responsables = $this->responsables;
        $estrategia->fecha_inicio = $this->fecha_inicio;
        $estrategia->fecha_fin = $this->fecha_fin;
        $estrategia->evidencias = $this->evidencias;
        $estrategia->presupuesto = $this->presupuesto;
        return $estrategia->save() ? $estrategia : null;
    }

}

<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use dektrium\rbac\components\DbManager;

/**
 * This is the model class for table "objetivos".
 *
 * @property integer $id
 * @property string $descripcion
 * @property string $responsables
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
            [['descripcion', 'responsables', 'fecha_inicio', 'fecha_fin'], 'required'],
            [['fecha_inicio', 'fecha_fin'], 'verifDate'],
            [['descripcion'], 'string', 'max' => 500],
            [['responsables'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'descripcion' => 'Descripcion',
            'responsables' => 'Responsables',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_fin' => 'Fecha Fin',
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
    public function getEstrategias() {
        return $this->hasMany(Estrategias::className(), ['id_objetivo' => 'id']);
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

    public function getResponsables($resp) {


        $query = new \yii\db\Query();
        $query->select(['niveles.*', 'title', 'org_id'])
                ->from('niveles')->where(['nid' => $resp]);

        $cmd = $query->createCommand();
        $levels = $cmd->queryAll();
        $textResp='';
        foreach ($levels as $responsable):
            $textResp.="(".$responsable['title'].") ";
        endforeach;
        return $textResp;
    }

}

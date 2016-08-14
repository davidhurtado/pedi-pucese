<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use \yii\db\Query;
use app\models\Objetivos;
use app\models\Estrategias;
use app\models\Proyectos;
use app\models\Programas;

class Reportes extends \yii\db\ActiveRecord {

    protected static $table;
    public $objetivos;
    public $estrategias;
    public $programas;
    public $proyectos;

    function __construct() {
        $this->setTableName("objetivos");
        $this->objetivos=Objetivos::find();
        $this->estrategias=Estrategias::find()->all();
        $this->programas=Programas::find();
        $this->proyectos=Proyectos::find();
    }

    public static function tableName() {
        return self::$table;
    }

    public static function setTableName($table) {
        self::$table = $table;
    }
    function getObjetivos() {
        $this->setTableName("objetivos");
        return $this->objetivos;
    }

    function getEstrategias() {
        $this->setTableName("estrategias");
        return $this->estrategias;
    }

    function getProgramas() {
        $this->setTableName("programas");
        return $this->programas;
    }

    function getProyectos() {
        $this->setTableName("proyectos");
        return $this->proyectos;
    }

        public function objetivos() {
        return $this->hasOne(Objetivos::className(), ['id' => 'id']);
    }

    public function estrategias($id) {
        return $this->hasOne(Estrategias::className(), ['id_objetivo' => $id]);
        //return Estrategias::findOne(['id_objetivo' => $id]);
    }

    public function proyectos($id) {
        return $this->hasOne(Proyectos::className(), ['id_programa' => $id]);
        //return Estrategias::findOne(['id_objetivo' => $id]);
    }

    public function programas($id) {
        return $this->findAll(['id_estrategia' => $id]);
    }

}

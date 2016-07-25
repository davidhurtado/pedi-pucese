<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

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
            [['id_objetivo', 'descripcion', 'responsables','evidencias', 'fecha_inicio', 'fecha_fin'], 'required'],
            [['id_objetivo'], 'integer'],
            [['fecha_inicio', 'fecha_fin'], 'verifDate'],
            [['presupuesto'], 'number'],
            [['descripcion'], 'string', 'max' => 500],
            [['responsables'], 'string', 'max' => 100],
            [['evidencias'], 'string', 'max' => 600],
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

    public function getDocumentFile() {
        Yii::$app->params['uploadPath'] = Yii::$app->basePath . '/web/pdf/estrategias/';
        return isset($this->evidencias) ? Yii::$app->params['uploadPath'] : null;
    }

    /**
     * fetch stored image url
     * @return string
     */
    public function getDocumentUrl() {
        Yii::$app->params['uploadUrl'] = Yii::$app->urlManager->baseUrl . '/web/pdf/estrategias/';
// return a default image placeholder if your source avatar is not found
        $evidencias = isset($this->evidencias) ? $this->evidencias : null;
        return Yii::$app->params['uploadUrl'] . $evidencias;
    }

    /**
     * Process deletion of image
     *
     * @return boolean the status of deletion
     */
    public function deleteDocument() {
        $file = $this->getDocumentFile().$this->getAttribute('evidencias')[2];

// check if file exists on server
        if (empty($file) || !file_exists($file)) {
            return false;
        }

// check if uploaded file can be deleted on server
        if (!unlink($file)) {
            return false;
        }

// if deletion successful, reset your file attributes
        $this->evidencias = null;

        return true;
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
    public function getProgramas() {
        return $this->hasMany(Programas::className(), ['id_estrategia' => 'id']);
    }

    public function getFechaObjetivo() {
        $modelObjetivo = Objetivos::findOne($_GET['id']);
        return $modelObjetivo;
    }

    public function getObjetivo($id) {
        $modelObjetivo = Objetivos::findOne($id)->descripcion;
        return $modelObjetivo;
    }

    public function getEvidencias_preview() {
        $aux = explode(';', $this->getAttribute('evidencias'));
        $evidencias_preview = Array();
        for ($i = 0; $i < count($aux)-1; $i++):
            array_push($evidencias_preview, $this->getDocumentFile() . 'pdf/estrategias/' . $aux[$i]);
        endfor;
        return $evidencias_preview ;
    }

    public function getEvidencias() {
        $aux = explode(';', $this->getAttribute('evidencias'));
        $evidencias = Array();
        for ($i = 0; $i < count($aux)-1; $i++):
            array_push($evidencias, [
                'type' => 'pdf',
                'caption' => $aux[$i],
                'url' => 'pdf/estrategias/' . $aux[$i],
                'key' => $i+1
            ]);
        endfor;
        return $evidencias;
    }

}

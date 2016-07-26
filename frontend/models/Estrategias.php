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
    public function getProgramas() {
        return $this->hasMany(Programas::className(), ['id_estrategia' => 'id']);
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

    public function getDocuments() {
        // get the uploaded file instance. for multiple file uploads
        // the following data will return an array (you may need to use
        // getInstances method)
        $image = UploadedFile::getInstances($this, 'evidencias');

        // if no image was uploaded abort the upload
        if (empty($image)) {
            return false;
        }
        $i = 0;
        $txtEvidencias = '';
        foreach ($image as $evide):
            $ext = end((explode(".", $evide->name)));
            // generate a unique file name
            $this->evidencias_array[$i] = Yii::$app->security->generateRandomString() . ".{$ext}";
            $txtEvidencias.= $this->evidencias_array[$i] . ';';
            $i++;
        endforeach;
        
        return $txtEvidencias;
    }

    public function uploadDocument() {
        // get the uploaded file instance. for multiple file uploads
        // the following data will return an array (you may need to use
        // getInstances method)
        $image = UploadedFile::getInstances($this, 'evidencias');

        // if no image was uploaded abort the upload
        if (empty($image)) {
            return false;
        }
        return $image;
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
        for ($i = 0; $i < count($aux) - 1; $i++):
            array_push($evidencias_preview, $this->getDocumentFile() . 'pdf/estrategias/' . $aux[$i]);
        endfor;
        return $evidencias_preview;
    }

    public function getEvidencias() {
        $aux = explode(';', $this->getAttribute('evidencias'));
        $key = $this->id;
        $evidencias = Array();
        $url = Url::to(['estrategias/view', 'id' => $key]);
        for ($i = 0; $i < count($aux) - 1; $i++):
            array_push($evidencias, [
                'type' => 'pdf',
                'caption' => $aux[$i],
                'url' => 'pdf/estrategias/' . $aux[$i],
                'key' => $aux[$i]
                    //'key' => $i + 1
            ]);
        endfor;
        return $evidencias;
    }

    public function getLevels() {


        $query = new \yii\db\Query();
        $query->select(['niveles.*', 'title', 'nid', 'org_id'])
                ->from('niveles')->where(['rid' => 7])
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

}

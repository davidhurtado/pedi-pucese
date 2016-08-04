<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "programas".
 *
 * @property integer $id
 * @property integer $id_estrategia
 * @property string $descripcion
 * @property string $responsables
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
            [['descripcion', 'responsables', 'fecha_inicio', 'fecha_fin'], 'required'],
            [['id_estrategia'], 'integer'],
            [['fecha_inicio', 'fecha_fin'], 'verifDate'],
            [['presupuesto'], 'number'],
            [['descripcion'], 'string', 'max' => 500],
            [['responsables'], 'string', 'max' => 100],
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
            'descripcion' => 'Descripcion',
            'responsables' => 'Responsables',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_fin' => 'Fecha Fin',
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
    public function getIdEstrategia() {
        return $this->hasOne(Estrategias::className(), ['id' => 'id_estrategia']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProyectos() {
        return $this->hasMany(Proyectos::className(), ['id_programa' => 'id']);
    }

    public function getLevels() {


        $query = new \yii\db\Query();
        $query->select(['niveles.*', 'title', 'nid', 'org_id'])
                ->from('niveles')//->where(['rid' => 9])
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
    public function savePrograma() {
        if (!$this->validate()) {
            return null;
        }
        $programa = new Programas();
        $programa->id_estrategia = $this->id_estrategia;
        $programa->descripcion = $this->descripcion;
        $programa->responsables = $this->responsables;
        $programa->fecha_inicio = $this->fecha_inicio;
        $programa->fecha_fin = $this->fecha_fin;
        $programa->presupuesto = $this->presupuesto;
        return $programa->save() ? $programa : null;
    }

}

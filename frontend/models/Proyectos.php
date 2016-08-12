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
            [['nombre', 'descripcion', 'responsables', 'fecha_inicio', 'fecha_fin'], 'required'],
            [['id_programa'], 'integer'],
            [['fecha_inicio', 'fecha_fin'], 'verifDate'],
            [['presupuesto'], 'number'],
            [['nombre'], 'string', 'max' => 200],
            [['descripcion'], 'string', 'max' => 500],
            [['responsables'], 'string', 'max' => 100],
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
            'descripcion' => 'Descripcion',
            'responsables' => 'Responsable',
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
    public function getIdPrograma() {
        return $this->hasOne(Programas::className(), ['id' => 'id_programa']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubproyectos() {
        return $this->hasMany(Subproyectos::className(), ['id_proyecto' => 'id']);
    }

    public function getFechas() {
        $model_ = Programas::findOne($_GET['id']);
        return $model_;
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
    public function saveProyecto() {
        if (!$this->validate()) {
            return null;
        }
        $proyecto = new Proyectos();
        $proyecto->id_programa = $this->id_programa;
        $proyecto->nombre = $this->nombre;
        $proyecto->descripcion = $this->descripcion;
        $proyecto->responsables = $this->responsables;
        $proyecto->fecha_inicio = $this->fecha_inicio;
        $proyecto->fecha_fin = $this->fecha_fin;
        $proyecto->presupuesto = $this->presupuesto;
        return $proyecto->save() ? $proyecto : null;
    }

}

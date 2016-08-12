<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "organigrama".
 *
 * @property integer $id
 * @property string $name
 * @property string $created
 *
 * @property Niveles[] $niveles
 */
class Organigrama extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'organigrama';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name'], 'string'],
            [['activo'], 'integer'],
            [['created'], 'safe'],
        ];
    }

    //  -----> CREAR REGLAS DE VALIDACIONES PARA Organigrama Unico activo    
    public function verifEstado($attribute) {
        $query = new \yii\db\Query();
        if ($this->$attribute == 1) {


            $query->select(['organigrama.*'])
                    ->from('organigrama')->where(['activo' => 1]);

            $cmd = $query->createCommand();
            $rows = $cmd->queryAll();

            // $cmd->queryAll()->
            if (count($rows)) {

                $this->addError($attribute, 'No pueden estar 2 organigramas activos');
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Nombre',
            'created' => 'Fecha de Creacion',
            'activo' => 'Estado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNiveles() {
        return $this->hasMany(Niveles::className(), ['org_id' => 'id']);
    }

}

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
class Organigrama extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'organigrama';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string'],
            [['created'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Nombre',
            'created' => 'Fecha de Creacion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNiveles()
    {
        return $this->hasMany(Niveles::className(), ['org_id' => 'id']);
    }
}

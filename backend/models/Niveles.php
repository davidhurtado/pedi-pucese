<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "niveles".
 *
 * @property integer $nid
 * @property string $title
 * @property integer $rid
 * @property integer $org_id
 *
 * @property Niveles $r
 * @property Niveles[] $niveles
 * @property Organigrama $org
 */
class Niveles extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'niveles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'string'],
            [['rid', 'org_id'], 'integer'],
            [['org_id'], 'required'],
            [['rid'], 'exist', 'skipOnError' => true, 'targetClass' => Niveles::className(), 'targetAttribute' => ['rid' => 'nid']],
            [['org_id'], 'exist', 'skipOnError' => true, 'targetClass' => Organigrama::className(), 'targetAttribute' => ['org_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nid' => 'Nid',
            'title' => 'Titulo',
            'rid' => 'Nivel Padre',
            'org_id' => 'Organigrama',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getR()
    {
        return $this->hasOne(Niveles::className(), ['nid' => 'rid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNiveles()
    {
        return $this->hasMany(Niveles::className(), ['rid' => 'nid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrg()
    {
        return $this->hasOne(Organigrama::className(), ['id' => 'org_id']);
    }

    public function getOrgChart(){


        $query = new \yii\db\Query();
        $query
            ->select(['organigrama.*','name','id', 'created'])
            ->from('organigrama')
            ->orderBy(['created' => SORT_DESC]);

        $cmd = $query->createCommand();
        $orgIDs = $cmd->queryAll();

        return $orgIDs;
    }

    public function getAllLevels(){


        $query = new \yii\db\Query();
        $query
            ->select(['niveles.*','title', 'nid', 'org_id'])
            ->from('niveles')
            ->orderBy(['title' => SORT_DESC]);

        $cmd = $query->createCommand();
        $levels = $cmd->queryAll();

        return $levels;
    }
}

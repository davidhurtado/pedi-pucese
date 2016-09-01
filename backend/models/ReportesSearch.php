<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Reportes;
use \yii\db\Query;

/**
 * EstrategiaSearch represents the model behind the search form about `app\models\Estrategia`.
 */
class ReportesSearch extends Reportes {

    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params, $table) {
        Reportes::setTableName($table);
        $query = Reportes::find()->orderBy('id');


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $this->load($params);
        $query->andFilterWhere([
            'id' => $this->id
        ]);

        return $dataProvider;
    }

}

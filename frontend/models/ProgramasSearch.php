<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Programas;

/**
 * ProgramasSearch represents the model behind the search form about `app\models\Programas`.
 */
class ProgramasSearch extends Programas {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'id_estrategia'], 'integer'],
            [['descripcion', 'responsables', 'fecha_inicio', 'fecha_fin'], 'safe'],
            [['presupuesto'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = Programas::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (isset($params['anio']) && !empty($params['anio'])) {
            $query->andFilterWhere(['<=', 'Extract(year from fecha_inicio)', $params['anio']])
                    ->andFilterWhere(['>=', 'Extract(year from fecha_fin)', $params['anio']]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'presupuesto' => $this->presupuesto,
        ]);

        $query->andFilterWhere(['like', 'descripcion', $this->descripcion])
                ->andFilterWhere(['like', 'responsables', $this->responsables]);

        return $dataProvider;
    }

}

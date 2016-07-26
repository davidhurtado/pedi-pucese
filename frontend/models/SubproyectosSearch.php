<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Subproyectos;

/**
 * SubproyectosSearch represents the model behind the search form about `app\models\Subproyectos`.
 */
class SubproyectosSearch extends Subproyectos {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'id_proyecto'], 'integer'],
            [['nombre', 'descripcion', 'evidencias', 'fecha_inicio', 'fecha_fin'], 'safe'],
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
        $query = Subproyectos::find();

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

        // grid filtering conditions
        if (isset($params['anio']) && !empty($params['anio'])) {
            $query->andFilterWhere(['<=', 'Extract(year from fecha_inicio)', $params['anio']])
                    ->andFilterWhere(['>=', 'Extract(year from fecha_fin)', $params['anio']]);
        }

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
                ->andFilterWhere(['like', 'descripcion', $this->descripcion])
                ->andFilterWhere(['like', 'evidencias', $this->evidencias]);

        return $dataProvider;
    }

}

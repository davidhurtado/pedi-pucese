<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Estrategias;

/**
 * EstrategiaSearch represents the model behind the search form about `app\models\Estrategia`.
 */
class EstrategiasSearch extends Estrategias {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'id_objetivo'], 'integer'],
            [['descripcion', 'colaboradores', 'fecha_inicio', 'fecha_fin', 'validacion'], 'safe'],
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
    public function search($params, $id = null) {

            $sql = Estrategias::find()->orderBy('id_objetivo, numeracion');
        // add conditions that should always apply here
        $query = $sql;
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
        $query->andFilterWhere([
            'id_objetivo' => $this->id_objetivo,
            'validacion' => $this->validacion,
        ]);
        // grid filtering conditions
        $query->andFilterWhere([
            'presupuesto' => $this->presupuesto,
        ]);

        $query->andFilterWhere(['like', 'descripcion', $this->descripcion])
                ->andFilterWhere(['like', 'colaboradores', $this->colaboradores]);

        return $dataProvider;
    }

}

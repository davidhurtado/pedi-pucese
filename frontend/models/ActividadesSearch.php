<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Actividades;

/**
 * ActividadesSearch represents the model behind the search form about `frontend\models\Actividades`.
 */
class ActividadesSearch extends Actividades {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'id_subproyecto', 'validacion'], 'integer'],
            [['descripcion', 'codigo_presupuestario', 'fecha_inicio', 'fecha_fin'], 'safe'],
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
        $query = Actividades::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'id_subproyecto' => $this->id_subproyecto,
            'presupuesto' => $this->presupuesto,
            'validacion' => $this->validacion,
        ]);
        if (!empty($this->fecha_inicio)) {
            $query->andFilterWhere(['between', 'fecha_inicio', $this->fecha_inicio . '-01-01', $this->fecha_inicio . '-12-31']);
        }
        if (!empty($this->fecha_fin)) {
            $query->andFilterWhere(['between', 'fecha_fin', $this->fecha_fin . '-01-01', $this->fecha_fin . '-12-31']);
        }


        $query->andFilterWhere(['like', 'descripcion', $this->descripcion])
                ->andFilterWhere(['like', 'codigo_presupuestario', $this->codigo_presupuestario]);

        return $dataProvider;
    }

}

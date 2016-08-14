<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Programas;

/**
 * ProgramasSearch represents the model behind the search form about `app\models\Programas`.
 */
class ProgramasSearch extends Programas
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_estrategia'], 'integer'],
            [['descripcion', 'responsables', 'fecha_inicio', 'fecha_fin'], 'safe'],
            [['presupuesto'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = Programas::find()->orderBy('id');

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
            'id_estrategia' => $this->id_estrategia,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'presupuesto' => $this->presupuesto,
        ]);

        $query->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'responsables', $this->responsables]);

        return $dataProvider;
    }
}

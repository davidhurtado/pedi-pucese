<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Estrategias;

/**
 * EstrategiaSearch represents the model behind the search form about `app\models\Estrategias`.
 */
class EstrategiaSearch extends Estrategias
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_objetivo'], 'integer'],
            [['descripcion', 'colaboradores', 'fecha_inicio', 'fecha_fin'], 'safe'],
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
        $query = Estrategias::find()->where(['validacion'=>1])->orderBy('id_objetivo,numeracion');

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
            'id_objetivo' => $this->id_objetivo,
        ]);
        if (!empty($this->fecha_inicio)) {
            $query->andFilterWhere(['between', 'fecha_inicio', $this->fecha_inicio . '-01-01', $this->fecha_inicio . '-12-31']);
        }
        if (!empty($this->fecha_fin)) {
            $query->andFilterWhere(['between', 'fecha_fin', $this->fecha_fin . '-01-01', $this->fecha_fin . '-12-31']);
        }


        $query->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'colaboradores', $this->colaboradores]);

        return $dataProvider;
    }
}

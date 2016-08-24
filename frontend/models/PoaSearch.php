<?php

namespace app\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Poa;
use app\models\Proyectos;
use \yii\db\Query;

/**
 * PoaSearch represents the model behind the search form about `frontend\models\Poa`.
 */
class PoaSearch extends Poa {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'estado'], 'integer'],
            [['fecha_creacion', 'fecha_ejecucion', 'fecha_fin'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function searchProyectos($params, $id) {
        $query = Proyectos::find(['id' => $id]);
        $query1 = new \yii\db\Query;
        $query1->select('*')->from('poa_proyectos');
        $query1->join('LEFT JOIN', 'proyectos', 'proyectos.id = poa_proyectos.id_proyecto');
        //$command=$query1->andWhere(['proyectos.estado' => 1]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query1,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

//        $query->andFilterWhere([
//            'id' => $this->id,
//            'id_programa' => $this->id_programa,
//            'fecha_inicio' => $this->fecha_inicio,
//            'fecha_fin' => $this->fecha_fin,
//            'presupuesto' => $this->presupuesto,
//        ]);
//
//        $query->andFilterWhere(['like', 'nombre', $this->nombre])
//                ->andFilterWhere(['like', 'descripcion', $this->descripcion])
//                ->andFilterWhere(['like', 'responsables', $this->responsables]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = Poa::find();
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
            'fecha_creacion' => $this->fecha_creacion,
            'fecha_ejecucion' => $this->fecha_ejecucion,
            'fecha_fin' => $this->fecha_fin,
            'estado' => $this->estado,
        ]);

        return $dataProvider;
    }

}

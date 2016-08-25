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
            [['fecha_creacion', 'fecha_ejecucion'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function searchProyectosPorValidar($params, $id) {
        $query = new \yii\db\Query;
        $query->select('*')->from('poa_proyectos')->where(['id_poa' => $id]);
        $command = $query->createCommand();
        $data = $command->queryAll();
        $array[] = 0;
        foreach ($data as $row) {
            array_push($array, $row['id_proyecto']);
        }
        $anio = date('Y', strtotime(Poa::findOne(['id' => $id])->fecha_ejecucion));
        /* --------------------------------- */
        $querySub = new \yii\db\Query;
        $querySub->select('*')->from('subproyectos')
                ->where(['estado' => 2])
                ->andWhere(['between','fecha_inicio',$anio . '-01-01',$anio . '-12-31'])
                ->andWhere(['between','fecha_fin',$anio . '-01-01',$anio . '-12-31'])
                //->andWhere(['fecha_fin' => $anio . '-12-31'],[])
                //->orWhere(['fecha_inicio'=>$anio . '-01-01'])
                ;
        $command2 = $querySub->createCommand();
        $data2 = $command2->queryAll();
        $array2[] = 0;
        //var_dump($data2);
        foreach ($data2 as $row) {
            array_push($array2, $row['id_proyecto']);
        }
        /* -------------------------------- */

        $query1 = new \yii\db\Query;
        $query1->select('*')->from('proyectos')
                ->where(['not in', 'id', $array])
                ->andWhere(['in', 'id', $array2])
                ->andWhere(['estado' => [1,2]])
                ;
        $dataProvider = new ActiveDataProvider([
            'query' => $query1,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        return $dataProvider;
    }

    public function searchProyectosValidados($params, $id) {
        $query = Proyectos::find(['id' => $id]);
        $query1 = new \yii\db\Query;
        $query1->select('*')->from('poa_proyectos');
        $query1->join('LEFT JOIN', 'proyectos', 'proyectos.id = poa_proyectos.id_proyecto');
        $query1->andWhere(['poa_proyectos.id_poa' => $id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query1,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }

    public function searchProyectosTerminados($params, $id) {
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
            return $dataProvider;
        }


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
            'estado' => $this->estado,
        ]);

        return $dataProvider;
    }

    public function searchPoaPorValidar($params) {
        $query = Poa::find()->where(['estado' => 1]);
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
            'estado' => $this->estado,
        ]);

        return $dataProvider;
    }

    public function searchPoaValidados($params) {
        $query = Poa::find()->where(['estado' => 2]);
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
            'estado' => $this->estado,
        ]);

        return $dataProvider;
    }

    public function searchPoaTerminados($params) {
        $query = Poa::find()->where(['estado' => 3]);
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
            'estado' => $this->estado,
        ]);

        return $dataProvider;
    }

}
?>

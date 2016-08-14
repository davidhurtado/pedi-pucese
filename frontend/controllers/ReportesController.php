<?php

namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use \app\models\Reportes;
use \app\models\Estrategias;
use \app\models\Objetivos;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\filters\AccessControl;
use app\models\ObjetivosSearch;
use app\models\EstrategiasSearch;
use app\models\ProgramasSearch;
use \app\models\ReportesSearch;

/**
 * Site controller
 */
class ReportesController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionObjetivosEstrategias() {
        $val = new Reportes();
//        var_dump($val);
//        $val->setTableName("estrategias");
//        echo '<br><br>';
//        var_dump($val->objetivos);
//        echo '<br><br>';
//        var_dump($val->estrategias);
//        echo '<br><br>';
//        var_dump($val->programas);
//        echo '<br><br>';
//        var_dump($val->proyectos);

        $search = new EstrategiasSearch();
        $dataProvider = $search->search(Yii::$app->request->queryParams);
        return $this->render('estrategias', [
                    'search' => $search,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionProgramas() {
        $search = new ProgramasSearch();
        $dataProvider = $search->search(Yii::$app->request->queryParams);

        return $this->render('programas', [
                    'search' => $search,
                    'dataProvider' => $dataProvider,
        ]);
    }

}

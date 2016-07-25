<?php

namespace frontend\controllers;

use Yii;
use app\models\Objetivos;
use app\models\ObjetivosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use app\models\Estrategias;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

/**
 * ObjetivosController implements the CRUD actions for Objetivos model.
 */
class ObjetivosController extends Controller {

    /**
     * @inheritdoc
     */
    public $evidencias_array = Array();

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Objetivos models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new ObjetivosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Objetivos model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {

        $dataProvider = new ActiveDataProvider([
            'query' => Estrategias::find()->where(['id_objetivo' => $id]),
        ]);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'dataProvider' => $dataProvider,
        ]);


//        $searchModel = new ObjetivosSearch();
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//        return $this->render('view', [
//                    'model' => $this->findModel($id),
//                    'dataProvider' => $dataProvider,
//        ]);
    }

    /**
     * Creates a new Objetivos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Objetivos();
        if ($model->load(Yii::$app->request->post())) {
            $model->responsables = implode(",", $model->responsables);
            if ($model->save()) {
                return $this->redirect(Yii::$app->request->referrer);
            }
        } elseif (Yii::$app->request->isAjax) {
            return $this->renderAjax('create', [
                        'model' => $model
            ]);
        } else {
            return $this->render('create', [
                        'model' => $model
            ]);
            //return $this->redirect(['index']);
        }
    }

    /**
     * Updates an existing Objetivos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    /* public function actionUpdate($id) {
      $model = $this->findModel($id);

      if ($model->load(Yii::$app->request->post()) && $model->save()) {
      return $this->redirect(['view', 'id' => $model->id]);
      } else {
      return $this->render('update', [
      'model' => $model,
      ]);
      }
      } */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
             $model->responsables = implode(",", $model->responsables);
            if ($model->save()) {
                return $this->redirect(['index']);
                return $this->redirect(Yii::$app->request->referrer);
            }
        } elseif (Yii::$app->request->isAjax) {
            return $this->renderAjax('update', [
                        'model' => $model
            ]);
        } else {
            return $this->render('update', [
                        'model' => $model
            ]);
            //return $this->redirect(['index']);
        }
    }

    /**
     * Deletes an existing Objetivos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Objetivos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Objetivos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Objetivos::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}

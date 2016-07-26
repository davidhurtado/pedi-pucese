<?php

namespace frontend\controllers;

use Yii;
use app\models\Programas;
use app\models\ProgramasSearch;
use app\models\Proyectos;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProgramasController implements the CRUD actions for Programas model.
 */
class ProgramasController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST','GET'],
                ],
            ],
        ];
    }

    /**
     * Lists all Programas models.
     * @return mixed
     */
    public function actionIndex() {
         $searchModel = new ProgramasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Programas model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $dataProvider = new ActiveDataProvider([
            'query' => Proyectos::find()->where(['id_programa' => $id]),
        ]);
        return $this->render('view', [
                    'model' => $this->findModel($id),
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Programas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Programas();
        if ($model->load(Yii::$app->request->post())) {
            $model->id_estrategia = $_GET['id'];
            $model->responsables = implode(",", $model->responsables);
            if ($model->save()) {
                return $this->redirect(Yii::$app->request->referrer);
            } else {
                return $this->renderAjax('create', [
                            'model' => $model,
                ]);
            }
        }elseif (Yii::$app->request->isAjax) {
            return $this->renderAjax('create', [
                        'model' => $model
            ]);
        }else{
            return $this->redirect(['index']);
        }
    }

    /**
     * Updates an existing Programas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
         $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
             $model->responsables = implode(",", $model->responsables);
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
                //return $this->redirect(Yii::$app->request->referrer);
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
     * Deletes an existing Programas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();
        
     return $this->redirect(Yii::$app->request->referrer);
        //return $this->redirect(['index']);
    }

    /**
     * Finds the Programas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Programas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Programas::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}

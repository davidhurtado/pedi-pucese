<?php

namespace frontend\controllers;

use Yii;
use app\models\Actividades;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\Response;

//Funcionando

/**
 * ActividadesController implements the CRUD actions for Actividades model.
 */
class ActividadesController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST', 'GET'],
                ],
            ],
        ];
    }

    /**
     * Lists all Actividades models.
     * @return mixed
     */
    public function actionIndex() {
        if (isset($_GET['pid'])) {
            $events = Actividades::find()->where(['id_subproyecto' => $_GET['pid']])->all();
        }else{
             $events = Actividades::find()->all();
        }
        $tasks = [];
        foreach ($events AS $eve) {
            //Testing
            $event = new \yii2fullcalendar\models\Event();
            $event->id = $eve->id;

            $time = new \DateTime('now', new \DateTimeZone('America/Guayaquil'));
            $currentDate = $time->format('Y-m-d h:m:s');


            $event->title = $eve->descripcion;
            $event->start = $eve->fecha_inicio;

            if ($eve->fecha_inicio > $currentDate) {
                $event->backgroundColor = 'green';
                $event->color = 'black';
            } else {
                $event->backgroundColor = 'red';
                $event->color = 'black';
            }

            $event->end = $eve->fecha_fin;
            $event->url = Url::to(['/actividades/update', 'id' => $eve->id]);

            $tasks[] = $event;
        }

        return $this->render('index', ['events' => $tasks,
        ]);
    }

    /**
     * Displays a single Actividades model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Actividades model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($date = null) {
        $model = new Actividades();
        //if ($date != null) {
        $model->fecha_inicio = date('Y-m-d h:m:s', strtotime($date));


        // ESTA PARTE PARA QUE RENDERICER CON AJAX AHI MISMO
        // Documentacion
        //http://www.yiiframework.com/doc-2.0/guide-input-validation.html

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //return $this->redirect(['view', 'id' => $model->id]);
            return $this->redirect(['index', 'pid' => $_GET['pid']]);
        } else {
            //print_r($model);
            //die();
            return $this->renderAjax('create', [
                        'model' => $model,
            ]);
        }
        // } else {
        //     return $this->redirect(['index']);
        // }
    }

    /**
     * Updates an existing Actividades model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Actividades model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the Actividades model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Actividades the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Actividades::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}

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
/**
 * ObjetivosController implements the CRUD actions for Objetivos model.
 */
class ObjetivosController extends Controller {

    /**
     * @inheritdoc
     */
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
    /*public function actionCreate() {
        $model = new Objetivos();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
//          
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(Yii::$app->request->referrer);
//        }elseif (Yii::$app->request->isAjax) {
//            return $this->renderAjax('create', [
//                        'model' => $model
//            ]);
//        } else {
//            return $this->render('create', [
//                        'model' => $model
//            ]);
//        }
//        
        if ($model->load(Yii::$app->request->post())) {

            // process uploaded image file instance
            $documento = $model->uploadDocument();
            //var_dump($documento);
            //print_r($documento);
            //die('1234');
            print_r($documento.'<br>');
            if ($model->save()) {
                // upload only if valid uploaded file instance found
                
                if ($documento !== false) {
                    $path = $model->getDocumentFile(); // BIEN
                    $documento->saveAs($path);
                }
                return $this->redirect(['index']);
            }else{
                echo 'no guardo';
            }
        } else {
            return $this->renderAjax('create', [
                        'model' => $model,
            ]);
        }
    }*/
public function actionCreate() {
        $model = new Objetivos();



        if ($model->load(Yii::$app->request->post())) {

            // process uploaded image file instance
            $image = $model->uploadDocument();

               print_r($model->getDocumentFile());
               //die();
            if ($model->save()) {
                // upload only if valid uploaded file instance found
                if ($image !== false) {
                    $path = $model->getDocumentFile(); // BIEN
                    $image->saveAs($path);
                } 
                return $this->redirect(['index']);
            } else {
                // error in saving model
             
                /* return $this->render('create', [
                        'model' => $model,
            ]);*/
            }
        } else {
            return $this->renderAjax('create', [
                        'model' => $model,
            ]);
        }
    }
    /**
     * Updates an existing Objetivos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    /*public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }*/
public function actionUpdate($id) {
        $model = $this->findModel($id);
        $oldFile = $model->getDocumentFile();
        $oldFoto = $model->evidencias;
        $oldFileName = $model->evidencias;

        if ($model->load(Yii::$app->request->post())) {
            // process uploaded image file instance
            $image = $model->uploadDocument();
            
            // revert back if no valid file instance uploaded
            if ($image === false) {
                $model->evidencias = $oldFoto;
                $model->evidencias = $oldFileName;
            }

            if ($model->save()) {
                // upload only if valid uploaded file instance found
                if ($image !== false && unlink($oldFile)) { // delete old and overwrite
                    $path = $model->getDocumentFile();
                    $image->saveAs($path);
                } 
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
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

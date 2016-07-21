<?php

namespace frontend\controllers;

use Yii;
use app\models\Estrategias;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * EstrategiasController implements the CRUD actions for Estrategias model.
 */
class EstrategiasController extends Controller {

    /**
     * @inheritdoc
     */
    public $evidencias_array = Array();

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
     * Lists all Estrategias models.
     * @return mixed
     */
    public function actionIndex() {
        $dataProvider = new ActiveDataProvider([
            'query' => Estrategias::find(),
        ]);

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Estrategias model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Estrategias model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Estrategias();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        $model->id_objetivo = $_GET['id'];

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $i = 0;
            $Evidencias = UploadedFile::getInstances($model, 'evidencias');
            $txtEvidencias = '';
            foreach ($Evidencias as $evide):
                $ext = end((explode(".", $evide->name)));
                // generate a unique file name
                $this->evidencias_array[$i] = Yii::$app->security->generateRandomString() . ".{$ext}";
                $txtEvidencias.= $this->evidencias_array[$i] . ';';
                $i++;
            endforeach;
            $connection = Yii::$app->db;
            $command = $connection->createCommand("UPDATE estrategias SET evidencias='" . $txtEvidencias . "' WHERE id=" . $model['id']);
            // ->bindValue(':id', $_GET['id']);
            $command->execute();
            //die();
            $path = $model->getDocumentFile();
            // upload only if valid uploaded file instance found
            $i = 0;
            foreach ($Evidencias as $file) {
                $ext = end((explode(".", $file->name)));
                // generate a unique file name
                $model->evidencias = $this->evidencias_array[$i];
                $file->saveAs($path . $model->evidencias);
                $i++;
            }
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->renderAjax('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Estrategias model.
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
     * Deletes an existing Estrategias model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionDeleteDocument() {
        // $this->findModel($id)->delete();
        echo 'yes';
        die();
        //return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the Estrategias model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Estrategias the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Estrategias::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}

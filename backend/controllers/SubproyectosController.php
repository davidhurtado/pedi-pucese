<?php

namespace backend\controllers;

use Yii;
use app\models\Subproyectos;
use app\models\SubproyectosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\filters\AccessControl;

/**
 * SubproyectosController implements the CRUD actions for Subproyectos model.
 */
class SubproyectosController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'update', 'view', 'create-index', 'delete', 'bulk-delete','delete-document'],
                        'allow' => true,
                        'roles' => ['admin', 'crear-proyecto', 'actualizar-proyecto'],
                    ],
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['admin', 'ACTUALIZAR_PROGRAMAS'],
                    ],
                ],
            ],
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
     * Lists all Subproyectos models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new SubproyectosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Subproyectos model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Subproyectos #" . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->render('view', [
                        'model' => $this->findModel($id),
            ]);
        }
    }

    public function actionUpdate($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Update Subproyectos #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {

                $image = $model->uploadDocument();
                $model->evidencias = $model->getDocuments();
                if ($image !== false) {
                    $path = $model->getDocumentFile();
                    $archivos = (explode(";", $model->evidencias));
                    $i = 0;
                    foreach ($image as $file) {
                        $ext = end((explode(".", $file->name)));
                        // generate a unique file name
                        $file->saveAs($path . $archivos[$i]);
                        $i++;
                    }
                }
                $model->evidencias = $model->oldAttributes['evidencias'] . $model->evidencias;
                $connection = Yii::$app->db;
                $command = $connection->createCommand("UPDATE subproyectos SET evidencias='" . $model->evidencias . "' WHERE id=" . $model->id);
                $command->execute();
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Subproyectos #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Update Subproyectos #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {
            return $this->redirect(['index']);
        }
    }

    /**
     * Delete an existing Subproyectos model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    public function actionDeleteDocument() {
        $model = $this->findModel($_GET['id']);
        if ($_GET['action'] == 'deletefile') {
            $file = Yii::$app->basePath . '/web/' . $_GET['file'];
        }
        $evidencias = str_replace($_GET['fileName'] . ';', '', $model->oldAttributes['evidencias']);
        echo ' -> ' . $evidencias;
        $connection = Yii::$app->db;
        $command = $connection->createCommand("UPDATE subproyectos SET evidencias='" . $evidencias . "' WHERE id=" . $_GET['id']);
        $command->execute();
        // check if file exists on server
        if (empty($file) || !file_exists($file)) {
            return false;
        }

        // check if uploaded file can be deleted on server
        if (!unlink($file)) {
            return false;
        }
        return true;
    }

    /**
     * Delete multiple existing Subproyectos model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBulkDelete() {
        $request = Yii::$app->request;
        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys
        foreach ($pks as $pk) {
            $model = $this->findModel($pk);
            $model->delete();
        }

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Subproyectos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Subproyectos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Subproyectos::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}

<?php

namespace frontend\controllers;

use Yii;
use app\models\Actividades;
use app\models\ActividadesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\Proyectos;
use app\models\Subproyectos;
use yii\filters\AccessControl;

/**
 * ActividadesController implements the CRUD actions for Actividades model.
 */
class ActividadesController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['create', 'update', 'index', 'view', 'create-index', 'delete', 'bulk-delete'],
                        'allow' => true,
                        'roles' => ['admin', 'superadmin', 'actividades'],
                    ],
                    [
                        'actions' => ['create', 'update', 'index', 'view', 'create-index', 'delete', 'bulk-delete'],
                        'allow' => true,
                        'roles' => ['crear-actividad', 'actualizar-actividad',],
                    ],
                    [
                        'actions' => ['index', 'view', 'delete', 'bulk-delete'],
                        'allow' => true,
                        'roles' => ['eliminar-actividad'],
                    ],
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['ver-actividad'],
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
     * Lists all Actividades models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new ActividadesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Actividades model.
     * @param integer $id
     * @return mixed
     */
    public function actionView() {
        $searchModel = new ActividadesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        /* $dataProvider = new ActiveDataProvider([
          'query' => Estrategias::find()->where(['id_objetivo' => $id]),
          ]); */
        return $this->render('view', [
                    'model' => $this->findModel(1),
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreateIndex() {
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
        // if ($request->isGet) {
        // $objetivo = $request->get('id');
        $model = new Actividades();
        if ($request->post('proyecto')) {
            $id = $request->post('proyecto');
            $busq = Subproyectos::find()->where(['id_proyecto' => $id])->andWhere(['estado' => 3])->one();
            $model->id_subproyecto = $busq->id;
            return [
                'title' => "Crear nueva Actividad",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                    'controlador' => 'proyectos',
                    'id' => $id,
                    'accion' => 'create',
                    'subproyecto' => $busq
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
            ];
        }

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            return [
                'title' => "Seleccione Proyecto",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                    'controlador' => 'actividades',
                    'accion' => 'create'
                        //'accion' => 'obj'
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                Html::button('Continuar', ['class' => 'btn btn-primary', 'type' => "submit"])
            ];
        }
    }

    /**
     * Creates a new Actividades model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id) {
        $request = Yii::$app->request;
        $model = new Actividades();
        $busq = Subproyectos::find()->where(['id_proyecto' => $id])->andWhere(['estado' => 3])->one();

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Crear nueva Actividad",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'controlador' => 'proyectos',
                        'id' => $id,
                        'accion' => 'create',
                        'subproyecto' => ''
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Create new Actividades",
                    'content' => '<span class="text-success">Create Actividades success</span>',
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Create More', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Crear nueva Actividad",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'controlador' => 'proyectos',
                        'id' => $id,
                        'accion' => 'create',
                        'subproyecto' => ''
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {
            return $this->redirect(['index']);
        }
    }

    /**
     * Updates an existing Actividades model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
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
                    'title' => "Update Actividades #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Actividades #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Update Actividades #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {
            return $this->redirect(['index']);
        }
    }

    /**
     * Delete an existing Actividades model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $request = Yii::$app->request;
        //$this->findModel($id)->delete();
        $model = new \yii\db\Query();
        $model->createCommand()->update('actividades', [
            'validacion' => 0,
                ], 'id=' . $id)->execute();
        $historial = new \yii\db\Query();
        $historial->createCommand()->insert('historial', [
            'usuario' => Yii::$app->user->identity->id,
            'ruta' => 'frontend',
            'tabla' => 'actividades',
            'id_objeto' => $id])->execute();
        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceCerrar' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Delete multiple existing Actividades model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBulkDelete() {
        $request = Yii::$app->request;
        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys
        foreach ($pks as $id) {
            //$model = $this->findModel($id);
            //$model->delete();
            $model = new \yii\db\Query();
            $model->createCommand()->update('actividades', [
                'validacion' => 0,
                    ], 'id=' . $id)->execute();
            $historial = new \yii\db\Query();
            $historial->createCommand()->insert('historial', [
                'usuario' => Yii::$app->user->identity->id,
                'ruta' => 'frontend',
                'tabla' => 'actividades',
                'id_objeto' => $id])->execute();
        }

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceCerrar' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
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

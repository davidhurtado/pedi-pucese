<?php

namespace frontend\controllers;

use Yii;
use app\models\Proyectos;
use app\models\ProyectosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use app\models\Subproyectos;
use yii\filters\AccessControl;
/**
 * ProyectosController implements the CRUD actions for Proyectos model.
 */
class ProyectosController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['create', 'update', 'index', 'view','create-index','delete','bulk-delete'],
                        'allow' => true,
                        'roles' => ['admin','crear-proyecto','actualizar-proyecto'],
                    ],
                    [
                        'actions' => ['update', 'index', 'view'],
                        'allow' => true,
                        'roles' => ['admin','ACTUALIZAR_PROGRAMAS'],
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
     * Lists all Proyectos models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new ProyectosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Proyectos model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $dataProvider = new ActiveDataProvider([
            'query' => Subproyectos::find()->where(['id_proyecto' => $id]),
        ]);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Proyectos model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateIndex() {
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
        // if ($request->isGet) {
        // $objetivo = $request->get('id');
        $model = new Proyectos();
        if ($request->post('programa')) {
            $estrategia = $request->post('programa');
            $model->id_programa = $estrategia;
            return [
                'title' => "Crear nuevo Proyecto",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                    'controlador' => 'programas',
                    'id' => $estrategia,
                    'accion' => 'create'
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
                'title' => "Seleccione Programa",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                    'controlador' => 'proyectos',
                    'accion' => 'create'
                        //'accion' => 'obj'
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                Html::button('Continuar', ['class' => 'btn btn-primary', 'type' => "submit"])
            ];
        }
    }

    public function actionCreate($id) {
        $request = Yii::$app->request;
        $model = new Proyectos();

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Crear nuevo Proyecto",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'controlador' => 'programas',
                        'id' => $id,
                        'accion' => 'create'
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else {

                if ($model->load(Yii::$app->request->post())) {
                    $model->id_programa = $id;
                    if ($model->validate()) {
                        $model->responsables = implode(",", $model->responsables);
                        if ($model->save()) {
                            return [
                                'forceReload' => '#crud-datatable-pjax',
                                'title' => "Crear nuevo Proyecto",
                                'content' => '<span class="text-success">Proyecto creado</span>',
                                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                Html::a('Crear M&aacute;s', ['create', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                            ];
                        } else {
                            return [
                                'forceReload' => '#crud-datatable-pjax',
                                'title' => "Error",
                                'content' => '<span class="text-success">Error al crear el programa, intente de nuevo</span>',
                                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                Html::a('Crear', ['create', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                            ];
                        }
                    } else {
                        return [
                            'title' => "Crear nuevo Proyecto",
                            'content' => $this->renderAjax('create', [
                                'model' => $model,
                                'controlador' => 'programas',
                                'id' => $id, //-->>
                                'accion' => 'create'
                            ]),
                            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                        ];
                    }
                } else {
                    return $this->redirect(['index']);
                }
            }
        } else {
            return $this->redirect(['index']);
        }
    }

    /**
     * Updates an existing Proyectos model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model_ = Proyectos::find()->where(['id' => $id])->one();
        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Actualizar Proyecto #" . $model->numeracion,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'controlador' => 'programas',
                        'accion' => 'update'
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {
                if ($model->validate()) {
                    $model->responsables = implode(",", $model->responsables);
                }

                if ($model->save()) {
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "Actualizado",
                        'content' => '<span class="text-success">Proyecto Actualizado</span>',
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    ];
                } else {
                    return [
                        'title' => "Actualizar Proyecto #" . $model_->numeracion,
                        'content' => $this->renderAjax('update', [
                            'model' => $model,
                            'controlador' => 'programas',
                            'accion' => 'update'
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                    ];
                }
            } else {
                return [
                    'title' => "Actualizar Proyecto #" . $model_->numeracion,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'controlador' => 'programas',
                        'accion' => 'update'
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
     * Delete an existing Proyectos model.
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

    /**
     * Delete multiple existing Proyectos model.
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
     * Finds the Proyectos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Proyectos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Proyectos::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}

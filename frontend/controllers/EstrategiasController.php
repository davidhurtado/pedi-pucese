<?php

namespace frontend\controllers;

use Yii;
use app\models\Estrategias;
use app\models\EstrategiaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use app\models\Programas;
use yii\filters\AccessControl;
/**
 * EstrategiasController implements the CRUD actions for Estrategias model.
 */
class EstrategiasController extends Controller {

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
                        'roles' => ['admin','crear-estrategia','actualizar-estrategia'],
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
     * Lists all Estrategias models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new EstrategiaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Estrategias model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $dataProvider = new ActiveDataProvider([
            'query' => Programas::find()->where(['id_estrategia' => $id]),
        ]);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Estrategias model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateIndex() {
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
        // if ($request->isGet) {
        // $objetivo = $request->get('id');
        $model = new Estrategias();
        if ($request->post('objetivo')) {
            $objetivo = $request->post('objetivo');
            $model = new Estrategias();
            $model->id_objetivo = $objetivo;
            return [
                'title' => "Crear nueva Estrategia",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                    'controlador' => 'objetivos',
                    'id' => $objetivo,
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
                'title' => "Seleccione Objetivo",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                    'controlador' => 'estrategias',
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
        $model = new Estrategias();
        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */

            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Crear nueva Estrategia",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'controlador' => 'objetivos',
                        'id' => $id,
                        'accion' => 'create'
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else {
                if ($model->load(Yii::$app->request->post())) {
                    $model->id_objetivo = $id;
                    if ($model->validate()) {
                        $model->responsables = implode(",", $model->responsables);
                        if ($model->save()) {
                            return [
                                'forceReload' => '#crud-datatable-pjax',
                                'title' => "Crear nueva Estrategia",
                                'content' => '<span class="text-success">Estrategia creada</span>',
                                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                Html::a('Crear M&aacute;s', ['create', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                            ];
                        } else {
                            return [
                                'forceReload' => '#crud-datatable-pjax',
                                'title' => "Error",
                                'content' => '<span class="text-success">Error al crear la estrategia, intente de nuevo</span>',
                                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                Html::a('Crear', ['create', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                            ];
                        }
                    } else {
                        return [
                            'title' => "Crear nueva Estrategia",
                            'content' => $this->renderAjax('create', [
                                'model' => $model,
                                'controlador' => 'objetivos',
                                'id' => $id, //-->>
                                'accion' => 'create'
                            ]),
                            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                        ];
                    }
                } else {
                    return [
                        'title' => "Crear nueva Estrategia",
                        'content' => $this->renderAjax('create', [
                            'model' => $model,
                            'controlador' => 'objetivos',
                            'id' => $id,
                            'accion' => 'create'
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                    ];
                }
            }
        } else {
            return $this->redirect(['index']);
        }
    }

    /**
     * Updates an existing Estrategias model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model_ = Estrategias::find()->where(['id' => $id])->one();
        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Actualizar Estrategia #" . $model->numeracion,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'controlador' => 'objetivos',
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
                        'content' => '<span class="text-success">Estrategia Actualizada</span>',
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    ];
                } else {
                    return [
                        'title' => "Actualizar Estrategia #" . $model_->numeracion,
                        'content' => $this->renderAjax('update', [
                            'model' => $model,
                            'controlador' => 'objetivos',
                            'accion' => 'update'
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                    ];
                }
            } else {
                return [
                    'title' => "Actualizar Estrategias #" . $model_->numeracion,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'controlador' => 'objetivos',
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
     * Delete an existing Estrategias model.
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
     * Delete multiple existing Estrategias model.
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

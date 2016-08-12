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

/**
 * EstrategiasController implements the CRUD actions for Estrategias model.
 */
class EstrategiasController extends Controller {

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
    public function actionCreate() {
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
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else {
                $model->id_objetivo = $_GET['id'];
                if ($model->load(Yii::$app->request->post())) {
                    if ($model->validate()) {
                        $model->responsables = implode(",", $model->responsables);
                        if ($model->save()) {
                            return [
                                'forceReload' => '#crud-datatable-pjax',
                                'title' => "Crear nueva Estrategia",
                                'content' => '<span class="text-success">Estrategia creada</span>',
                                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                Html::a('Crear M&aacute;s', ['create', 'id' => $_GET['id']], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                            ];
                        }else{
                            return [
                                'forceReload' => '#crud-datatable-pjax',
                                'title' => "Error",
                                'content' => '<span class="text-success">Error al crear la estrategia, intente de nuevo</span>',
                                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                Html::a('Crear', ['create', 'id' => $_GET['id']], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                            ];
                        }
                    } else {
                        return [
                            'title' => "Crear nueva Estrategia",
                            'content' => $this->renderAjax('create', [
                                'model' => $model,
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
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                    ];
                }
            }
        } else {
            /*
             *   Process for non-ajax request
             */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                            'model' => $model,
                ]);
            }
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

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Actualizar Estrategia #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {
                if ($model->validate()) {
                    $model->responsables = implode(",", $model->responsables);
                }

                if ($model->save()) {
                    return $this->redirect(Yii::$app->request->referrer);
                } else {
                    return [
                        'title' => "Actualizar Estrategia #" . $id,
                        'content' => $this->renderAjax('update', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                    ];
                }
            } else {
                return [
                    'title' => "Actualizar Estrategias #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {
            /*
             *   Process for non-ajax request
             */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                            'model' => $model,
                ]);
            }
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

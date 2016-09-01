<?php

namespace backend\controllers;

use Yii;
use app\models\Objetivos;
use app\models\ObjetivosSearch;
use app\models\Estrategias;
use app\models\Programas;
use app\models\EstrategiasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;

/**
 * ObjetivosController implements the CRUD actions for Objetivos model.
 */
class ObjetivosController extends Controller {

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
                        'roles' => ['admin', 'superadmin'],
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
        $searchModel = new EstrategiasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);

        /* $dataProvider = new ActiveDataProvider([
          'query' => Estrategias::find()->where(['id_objetivo' => $id]),
          ]); */
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Objetivos model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $request = Yii::$app->request;
        $model = new Objetivos();

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Crear nuevo Objetivo",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else
            if ($model->load(Yii::$app->request->post())) {
                if ($model->validate()) {
                    $model->responsable = Yii::$app->user->identity->id;
                    $model->colaboradores = implode(",", $model->colaboradores);
                    if ($model->save()) {
                        return [
                            'forceReload' => '#crud-datatable-pjax',
                            'title' => "Crear nuevo Objetivo",
                            'content' => '<span class="text-success">Objetivo creado</span>',
                            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Crear M&aacute;s', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                        ];
                    } else {
                        return [
                            'forceReload' => '#crud-datatable-pjax',
                            'title' => "Error",
                            'content' => '<span class="text-success">Error al crear el objetivo, intente de nuevo</span>',
                            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Crear', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                        ];
                    }
                } else {
                    return [
                        'title' => "Crear nuevo Objetivo",
                        'content' => $this->renderAjax('create', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                    ];
                }
            } else {
                return [
                    'title' => "Crear nuevo Objetivo",
                    'content' => $this->renderAjax('create', [
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
     * Updates an existing Objetivos model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $dataProvider = new ActiveDataProvider([
            'query' => Estrategias::find()->where(['id_objetivo' => $id]),
        ]);
        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Actualizar Objetivo #" . $model->numeracion,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {
                if ($model->validate()) {
                    $model->colaboradores = implode(",", $model->colaboradores);
                }

                if ($model->save()) {
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "Actualizado",
                        'content' => '<span class="text-success">Objetivo Actualizado</span>',
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    ];
                } else {
                    $model_ = Objetivos::find()->where(['id' => $id])->one();
                    return [
                        'title' => "Actualizar Objetivo #" . $model_->numeracion,
                        'content' => $this->renderAjax('update', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                    ];
                }
            } else {
                return [
                    'title' => "Actualizar Objetivo #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'dataProvider' => $dataProvider,
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
     * Delete an existing Objetivos model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $request = Yii::$app->request;
        //$this->findModel($id)->delete();
        $model = new \yii\db\Query();
        $model2 = new \yii\db\Query();
        $model3 = new \yii\db\Query();
        $model4 = new \yii\db\Query();
        $model->createCommand()->update('objetivos', [
            'validacion' => 0,
                ], 'id=' . $id)->execute();
        $estrategias = Estrategias::find()->where(['id_objetivo' => $id])->all();
        foreach ($estrategias as $data):
            $programas = Programas::find()->where(['id_estrategia' => $data['id']])->all();
            foreach ($programas as $data_programa):
                $model2->createCommand()->update('proyectos', [
                    'validacion' => 0,
                        ], 'id_programa=' . $data_programa['id'])->execute();
            endforeach;
            $model3->createCommand()->update('programas', [
                'validacion' => 0,
                    ], 'id_estrategia=' . $data['id'])->execute();
        endforeach;

        $model4->createCommand()->update('estrategias', [
            'validacion' => 0,
                ], 'id_objetivo=' . $id)->execute();
        $historial = new \yii\db\Query();
        $historial->createCommand()->insert('historial', [
            'usuario' => Yii::$app->user->identity->id,
            'ruta' => 'frontend',
            'tabla' => 'objetivos',
            'id_objeto' => $id])->execute();
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
     * Delete multiple existing Objetivos model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBulkDelete() {
        $request = Yii::$app->request;
        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys

        foreach ($pks as $id) {
            //$model = $this->findModel($pk);
            //$model->delete();
            $model = new \yii\db\Query();
            $model2 = new \yii\db\Query();
            $model3 = new \yii\db\Query();
            $model4 = new \yii\db\Query();
            $model->createCommand()->update('objetivos', [
                'validacion' => 0,
                    ], 'id=' . $id)->execute();
            $estrategias = Estrategias::find()->where(['id_objetivo' => $id])->all();
            foreach ($estrategias as $data):
                $programas = Programas::find()->where(['id_estrategia' => $data['id']])->all();
                foreach ($programas as $data_programa):
                    $model2->createCommand()->update('proyectos', [
                        'validacion' => 0,
                            ], 'id_programa=' . $data_programa['id'])->execute();
                endforeach;
                $model3->createCommand()->update('programas', [
                    'validacion' => 0,
                        ], 'id_estrategia=' . $data['id'])->execute();
            endforeach;

            $model4->createCommand()->update('estrategias', [
                'validacion' => 0,
                    ], 'id_objetivo=' . $id)->execute();

            $historial = new \yii\db\Query();
            $historial->createCommand()->insert('historial', [
                'usuario' => Yii::$app->user->identity->id,
                'ruta' => 'backend',
                'tabla' => 'objetivos',
                'id_objeto' => $id])->execute();
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

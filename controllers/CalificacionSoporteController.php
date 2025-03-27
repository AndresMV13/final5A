<?php

namespace app\controllers;
use Yii;
use app\models\CalificacionSoporte;
use app\models\CalificacionSoporteSearch;
use app\models\Tickets;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;/**
 * CalificacionSoporteController implements the CRUD actions for CalificacionSoporte model.
 */
class CalificacionSoporteController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all CalificacionSoporte models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CalificacionSoporteSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CalificacionSoporte model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CalificacionSoporte model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($id)
    {
        $modelTicket = Tickets::findOne($id); // Recuperar el modelo del ticket

    if (!$modelTicket) {
        throw new NotFoundHttpException('El ticket no existe.');
    }

    $modelCalificacion = new CalificacionSoporte(); // Crear un nuevo modelo de calificación

    if ($modelCalificacion->load(Yii::$app->request->post()) && $modelCalificacion->save()) {
        Yii::$app->session->setFlash('success', 'Gracias por contestar la encuesta');
        return $this->redirect(['tickets/my-tickets']);    }

    return $this->render('create', [
        'modelCalificacion' => $modelCalificacion,
        'modelTicket' => $modelTicket, // Pasar el modelo del ticket a la vista
    ]);
    }

    /**
     * Updates an existing CalificacionSoporte model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing CalificacionSoporte model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CalificacionSoporte model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return CalificacionSoporte the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CalificacionSoporte::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

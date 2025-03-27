<?php

namespace app\controllers;


use Yii;
use app\models\UsuarioServicio;
use app\models\Servicio;
use app\models\ServicioSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ServicioController implements the CRUD actions for Servicio model.
 */
class ServicioController extends Controller
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
     * Lists all Servicio models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ServicioSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Servicio model.
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


    public function actionContratarServicio()
{
    $servicios = Servicio::find()->all(); // Obtener todos los servicios
    return $this->render('contratar-servicio', [
        'servicios' => $servicios,
    ]);
}

public function actionContratar($id)
{
    $id_usuario = Yii::$app->user->id;

    // Buscar si el usuario ya tiene contratado el servicio
    $usuarioServicio = UsuarioServicio::findOne([
        'id_usuario' => $id_usuario,
        'id_servicio' => $id
    ]);

    if ($usuarioServicio) {
        // Si ya está contratado, cambia el estado entre 'activo' y 'cancelado'
        $usuarioServicio->estatus = ($usuarioServicio->estatus == 'activo') ? 'inactivo' : 'activo';

        if ($usuarioServicio->save()) {
            Yii::$app->session->setFlash('success', 'Estado del servicio actualizado correctamente.');
        } else {
            Yii::$app->session->setFlash('error', 'Error al actualizar el servicio: ' . json_encode($usuarioServicio->getErrors()));
        }
    } else {
        // Si no existe, creamos un nuevo registro con estatus "activo"
        $usuarioServicio = new UsuarioServicio();
        $usuarioServicio->id_usuario = $id_usuario;
        $usuarioServicio->id_servicio = $id;
        $usuarioServicio->estatus = 'activo';

        if ($usuarioServicio->save()) {
            Yii::$app->session->setFlash('success', 'Servicio contratado exitosamente.');
        } else {
            Yii::$app->session->setFlash('error', 'Error al contratar: ' . json_encode($usuarioServicio->getErrors()));
        }
    }

    return $this->redirect(['contratar-servicio']);
}






    /**
     * Creates a new Servicio model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Servicio();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Servicio model.
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
     * Deletes an existing Servicio model.
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
     * Finds the Servicio model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Servicio the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Servicio::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

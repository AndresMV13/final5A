<?php

namespace app\controllers;
use Yii;
use app\models\Servicio;
use app\models\User;
use app\models\UsuarioServicio;
use app\models\UsuarioServicioSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UsuarioServicioController implements the CRUD actions for UsuarioServicio model.
 */
class UsuarioServicioController extends Controller
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
     * Lists all UsuarioServicio models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UsuarioServicioSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UsuarioServicio model.
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
     * Creates a new UsuarioServicio model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new UsuarioServicio();

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

    public function actionMisSuscripciones()
{
    // Obtener las suscripciones activas del usuario logueado
    $suscripciones = UsuarioServicio::find()
        ->where(['id_usuario' => Yii::$app->user->id])
        ->andWhere(['in', 'estatus', ['activo', 'pendiente']])
        ->all();
    
    return $this->render('mis-suscripciones', ['suscripciones' => $suscripciones]);
}




    /**
     * Updates an existing UsuarioServicio model.
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
     * Deletes an existing UsuarioServicio model.
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
     * Finds the UsuarioServicio model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return UsuarioServicio the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UsuarioServicio::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionSuscribir($id_servicio)
    {
        $servicio= Servicio::findOne($id_servicio);
        $model = new UsuarioServicio();
        $model->id_usuario = Yii::$app->user->id;
        $model->id_servicio = $id_servicio;
        $model->precio_contratado = $servicio->precio;
        $model->estatus = 'activo';
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Suscripción activada.');
        }
        return $this->redirect(['usuario-servicio/mis-suscripciones']);
    }

    public function actionCancelar($id)
    {
        $model = UsuarioServicio::findOne($id);
        if ($model && $model->id_usuario == Yii::$app->user->id) {
            $model->estatus = 'pendiente';
            $model->save();
            Yii::$app->session->setFlash('success', 'Solicitud de cancelación enviada.');
        }
        return $this->redirect(['usuario-servicio/mis-suscripciones']);
    }

    public function actionAceptarCancelacion($id)
    {
        $model = UsuarioServicio::findOne($id);
        if ($model) {
            $model->estatus = 'inactivo';
            $model->fecha_cancelacion = date('Y-m-d H:i:s');
            $model->save();
            Yii::$app->session->setFlash('success', 'Suscripción cancelada.');
        }
        return $this->redirect(['usuario-servicio/pendientes']);
    }

    public function actionRechazarCancelacion($id)
    {
        $model = UsuarioServicio::findOne($id);
        if ($model) {
            $model->estatus = 'activo';
            $model->save();
            Yii::$app->session->setFlash('success', 'Cancelación rechazada.');
        }
        return $this->redirect(['usuario-servicio/pendientes']);
    }

    public function actionPendientes()
    {
        $pendientes = UsuarioServicio::find()->where(['estatus' => 'pendiente'])->all();
        return $this->render('pendientes', ['pendientes' => $pendientes]);
    }
}

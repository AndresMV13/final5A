<?php

namespace app\controllers;
use Yii;
use app\models\User;
use app\models\Tickets;
use app\models\TicketsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TicketsController implements the CRUD actions for Tickets model.
 */
class TicketsController extends Controller
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
     * Lists all Tickets models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TicketsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionMyTickets()
    {   if(Yii::$app->user->identity->rol->nombre=='Cliente'){
        $user= Yii::$app->user->identity;
        $dataProvider = $user->getTicketsAQ();
        $dataProvider->query->orderBy([
            'estado'=>SORT_ASC,
            'fecha_creacion'=>SORT_DESC,
            'n_serie'=>SORT_ASC
            ]);
    }elseif(Yii::$app->user->identity->rol->nombre=='Operador'){
        $user= Yii::$app->user->identity;
        $dataProvider= $user->getTickets0AQ();
        $dataProvider->query->orderBy([
        'estado'=>SORT_ASC,
        'n_serie'=>SORT_ASC,
        'fecha_creacion'=>SORT_DESC,
        ]);
    }elseif(Yii::$app->user->identity->rol->nombre=='Administrador'){
        $searchModel = new TicketsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->query->orderBy([
            'estado'=>SORT_ASC,
            'n_serie'=>SORT_ASC,
            'fecha_creacion'=>SORT_ASC,
            ]);
    }

        return $this->render('my-tickets', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Tickets model.
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
     * Creates a new Tickets model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Tickets();

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

    public function actionLevantarTicket(){
        $model= new Tickets();
        $id_cliente = Yii::$app->user->identity->id;
            $servicios = (new \yii\db\Query())
            ->select(['nombre','servicio.id'])
            ->from('servicio')
            ->innerJoin('usuario_servicio','usuario_servicio.id_servicio = servicio.id')
            ->where(['usuario_servicio.id_usuario'=>Yii::$app->user->identity->id,'usuario_servicio.estatus'=>'activo'])
            ->all();
        if($model->load($this->request->post())){
            
            $id_servicio = (int) $model->id_servicio;
            $description=$model->descripcion;
            $db=Yii::$app->db;
            $command=$db->createCommand("CALL ticket_insert(:id_cliente,:id_servicio,:descripcion,@_id,@_error)");
            $command->bindValue(':id_cliente',$id_cliente);
            $command->bindValue(':id_servicio',$id_servicio);
            $command->bindValue(':descripcion',$description);
            $command->execute();
            $result = $db->createCommand("SELECT @_id AS id, @_error AS error")->queryOne();

            if ($result['error'] == 0) {
                Yii::$app->session->setFlash('success', 'Ticket abierto, un operador se pondra en contacto contigo');
                return $this->redirect(['my-tickets']);
            } else {
                Yii::$app->session->setFlash('error', 'Error al levantar el ticket. Código: ' . $result['error']);
            }
            
        }
    
            
        

        return $this->render('levantar-ticket',[
            'model'=>$model,
            'servicios'=>$servicios,
        ]);
    
}
    

    /**
     * Updates an existing Tickets model.
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

    public function actionCerrarTicket($id) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    
        if (!Yii::$app->request->isAjax) {
            return ['success' => false, 'error' => 'Solicitud no válida.'];
        }
    
        $model = Tickets::findOne($id);    
        if (!$model) {
            return ['success' => false, 'error' => 'Ticket no encontrado.'];
        }
        $usuarioRol = Yii::$app->user->identity->rol->nombre;
        $ultimaRespuesta = $model->getLastMessage();
    
        if (!$ultimaRespuesta) {
            return ['success' => false, 'error' => 'No se puede cerrar el ticket porque no hay respuestas.'];
        }
    
        if ($usuarioRol === 'Cliente') {
            $model->estado = 'cerrado';
            if ($model->save()) {
                return ['success' => true, 'redirect' => 'calificacion-soporte/create', 'id'=> $id]; // Redirigir a la encuesta
            } else {
                return ['success' => false, 'error' => 'Hubo un error al cerrar el ticket.'];
            }
        } elseif ($usuarioRol === 'Operador') {
            $diferenciaHoras = (time() - strtotime($ultimaRespuesta['fecha'])) / 3600;
    
            // Verificar si han pasado más de 24 horas desde la última respuesta del cliente
            if ($ultimaRespuesta['id_rol'] == 3 && $diferenciaHoras >= 24) {
                $model->estado = 'cerrado';
                if ($model->save()) {
                    return ['success' => true, 'redirect' => 'tickets/my-tickets']; // Redirigir al listado de tickets sin encuesta
                } else {
                    return ['success' => false, 'error' => 'Hubo un error al cerrar el ticket.'];
                }
            } else {
                Yii::$app->session->setFlash('error', 'No puedes cerrar este ticket aún.');
            }
        }
    
        return ['success' => false, 'error' => 'No tienes permiso para cerrar este ticket.'];
    }
    
    
    
    /**
     * Deletes an existing Tickets model.
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
     * Finds the Tickets model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Tickets the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tickets::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

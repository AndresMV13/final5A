<?php

namespace app\controllers;
use Yii;
use app\models\User;
use app\models\UsuarioSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Rol;;

/**
 * UsuarioController implements the CRUD actions for Usuario model.
 */
class UsuarioController extends Controller
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
     * Lists all Usuario models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UsuarioSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->query->andWhere(['!=', 'id_rol', '3']);
        $dataProvider->query->andWhere(['status' => User::STATUS_ACTIVO]);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Usuario model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {   
        if(Yii::$app->user->identity->rol->nombre=='Cliente'){
            $id=Yii::$app->user->identity->id;
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Usuario model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new User();

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
    /*
    Tengo ganas de ser aire
    Y me respires para siempre
    Pues no tengo nada que perder
    Y todo el tiempo estoy pensando en ti
    En el brillo del Sol y en un rincón del cielo
    Todo el tiempo estoy pensando en ti
    En el eco del mar que retumba en tus ojos, soñé

    Action create utilizando el procedimiento almacenado para uso correcto 
    de la encriptacion de la contraseña.
    */
    public function CreateSP($model){
        if($model->load(Yii::$app->request->post())){

            $nombre=$model->nombre;
            $apellido_paterno=$model->apellido_paterno;
            $apellido_materno=$model->apellido_materno;
            $correo=$model->correo;
            $password=$model->password;
            $id_rol=$model->id_rol;
            $status=$model->status;

            $db= Yii::$app->db;
            $command= $db->createCommand("CALL usuario_insert(:_rol,:_nombre,:_apellido_paterno,:_apellido_materno,:_correo,:_password,:_status,@_id,@_error)");
            $command->bindValue(':_rol',$id_rol);
            $command->bindValue(':_nombre',$nombre);
            $command->bindValue(':_apellido_paterno',$apellido_paterno);
            $command->bindValue(':_apellido_materno',$apellido_materno);
            $command->bindValue(':_correo',$correo);
            $command->bindValue(':_password',$password);
            $command->bindValue(':_status',$status);
            $command->execute();
            $result = $db->createCommand("SELECT @_id AS id, @_error AS error")->queryOne();

            return $result;
        }
        return null;
    
    }
    /* 
        Implementamos un action create para los distintntos tipos de 
        vistas a la hora de crear, una para el staff o personal que hara el Administrador
        y por uno mas para los clielntes que se vayan a dar de alta en la plataforma
    */
    public function actionCreateClientes(){
        $model= new User();
        if ($model->load($this->request->post())) {
            $result = $this->CreateSP($model);
    
            if ($result) {
                if ($result['error'] == 0) {
                    Yii::$app->session->setFlash('success', 'Registrado correctamente, Inicia Sesion');
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->session->setFlash('error', 'Error al crear el cliente. Código: ' . $result['error']);
                }
            } else {
                Yii::$app->session->setFlash('error', 'Error inesperado al ejecutar el procedimiento almacenado.');
            }
        }
        return $this->render('create-clientes', [
            'model' => $model,
        ]);
    }

    public function actionCreateStaff(){
        $roles = Rol::find()
        ->where(['<>', 'id', 3])
        ->select(['nombre', 'id'])
        ->indexBy('id')
        ->column();
        $model= new User();

        if ($model->load($this->request->post())) {
            /** 
            Transformamos el id del rol a entero ya que el formula
            */
            $model->id_rol = (int) $model->id_rol;
            $result = $this->CreateSP($model);
            if ($result) {
                if ($result['error'] == 0) {
                    Yii::$app->session->setFlash('success', 'Miembro del staff creado correctamente. ID: ' . $result['id']);
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->session->setFlash('error', 'Error al crear el cliente. Código: ' . $result['error']);
                }
            } else {
                Yii::$app->session->setFlash('error', 'Error inesperado al ejecutar el procedimiento almacenado.');
            }
        }
        return $this->render('create-staff', [
            'model' => $model,
            'roles'=>$roles,
        ]);
    }
    

    

    
    /**
     * Updates an existing Usuario model.
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
     * Deletes an existing Usuario model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->setStatusToInactivo();
        Yii::$app->session->setFlash('success', 'Miembro del staff inhabilitado');
        return $this->redirect(['index']);
    }

    /**
     * Finds the Usuario model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Usuario the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

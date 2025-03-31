<?php

namespace app\controllers;
use Yii;
use app\models\User;
use app\models\UsuarioSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Rol;;
use yii\data\ActiveDataProvider;

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
        $dataProvider->query->andWhere(['!=', 'id_rol', '1']);
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
    // Controlador UsuarioController.php

public function actionView($id)
{
    $model = $this->findModel($id);  // Obtén el operador por su ID

    $dataProvider2 = new ActiveDataProvider([
        'query' => \app\models\CalificacionSoporte::find()
            ->select([
                'numero_serie', 
                'ROUND((IFNULL(p1, 0) + IFNULL(p2, 0) + IFNULL(p3, 0) + IFNULL(p4, 0) + IFNULL(p5, 0)) / 5, 2) AS promedio'
            ])
            ->where(['id_operador' => $model->id])  // Filtramos por operador_id
            ->orderBy(['numero_serie' => SORT_DESC]),
        'pagination' => [
            'pageSize' => 10,  // Número de registros por página
        ],
    ]);

    return $this->render('view', [
        'model' => $model,
        'dataProviderCalificaciones' => $dataProvider2,
    ]);
}


    public function actionMyView()
    {
        $id=Yii::$app->user->identity->id;
        return $this->render('my-view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionUpdatePassword()
{
    $model = $this->findModel(Yii::$app->user->identity->id);
    $model->scenario = 'changePassword';

    if (Yii::$app->request->isPost) {
        $model->load(Yii::$app->request->post());
        
        if (!$model->validate()) {
            Yii::$app->session->setFlash('error', 'Por favor corrija los errores en el formulario');
            return $this->render('update-password', ['model' => $model]);
        }

        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        
        try {
            // Llamar al procedimiento almacenado
            $command = $db->createCommand("CALL update_user_password(:correo, :current, :new, @error)")
                ->bindValue(':correo', Yii::$app->user->identity->correo)
                ->bindValue(':current', $model->currentPassword)
                ->bindValue(':new', $model->newPassword);
            
            // Ejecutar el procedimiento
            $command->execute();
            
            // Cerrar cualquier resultado pendiente
            $command->pdoStatement->closeCursor();
            
            // Obtener el código de error
            $errorCode = $db->createCommand("SELECT @error")->queryScalar();
            
            // Manejar el resultado
            switch ($errorCode) {
                case 0: // Éxito
                    $transaction->commit();
                    Yii::$app->user->logout();
                    Yii::$app->session->setFlash('success', 'Contraseña actualizada correctamente. Por favor inicie sesión nuevamente.');
                    return $this->redirect(['site/login']);
                
                case 3: // Usuario no encontrado
                    throw new \Exception('Usuario no registrado en el sistema');
                
                case 4: // Contraseña incorrecta
                    $model->addError('currentPassword', 'La contraseña actual es incorrecta');
                    break;
                
                case 1: // Error SQL
                    throw new \Exception('Error en la base de datos');
                
                case 2: // Advertencia SQL
                    throw new \Exception('Problema con la base de datos');
                
                default:
                    throw new \Exception('Error al actualizar la contraseña');
            }
            
            $transaction->rollBack();
            
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error("Error actualizando contraseña: " . $e->getMessage(), __METHOD__);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
    }

    return $this->render('update-password', ['model' => $model]);
}
    





    public function actionViewStaff($id)
    {
        return $this->render('view-staff', [
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
            **/
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

<?php

namespace app\controllers;
use Yii;
use yii\helpers\Html;
use app\models\User;
use app\models\UsuarioSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Rol;;
use yii\data\ActiveDataProvider;
use app\models\Tickets;

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
        $dataProvider->query->orderBy([
            'nombre'=>SORT_DESC]);

            if (!Yii::$app->user->isGuest && Yii::$app->user->identity->hasRole(1)) {
                return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            } else {
                return $this->render('..\site\index');
            }
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

    
    
    if (!Yii::$app->user->isGuest && Yii::$app->user->identity->hasRole(1)) {
        return $this->render('view', [
            'model' => $model,
            'dataProviderCalificaciones' => $dataProvider2,
        ]);
    } else {
        return $this->render('..\site\index');
    }

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
    

public function actionRecuperarContrasena()
{
    $model = new \app\models\FormRecuperar(); // solo tiene 'correo'

    if ($model->load(Yii::$app->request->post())) {
        $usuario = User::find()->where(['correo' => $model->correo])->one();

        if ($usuario) {
            Yii::$app->session->set('usuario_para_seguridad', $usuario->id);
            return $this->redirect(['usuario/verificar-seguridad']);
        } else {
            Yii::$app->session->setFlash('error', 'Correo no encontrado.');
        }
    }

    return $this->render('recuperar-contrasena', ['model' => $model]);
}

public function actionVerificarSeguridad()
{
    // 1. Verificar sesión
    $id = Yii::$app->session->get('usuario_para_seguridad');
    if (!$id) {
        Yii::debug('No se encontró ID de usuario en sesión');
        return $this->redirect(['usuario/recuperar-contrasena']);
    }

    // 2. Obtener usuario
    $usuario = User::findOne($id);
    if (!$usuario) {
        Yii::debug("Usuario con ID $id no encontrado en BD");
        Yii::$app->session->setFlash('error', 'Usuario no encontrado.');
        return $this->redirect(['usuario/recuperar-contrasena']);
    }

    // 3. Verificar si el usuario tiene pregunta/respuesta configurada
    if (empty($usuario->pregunta_seguridad) || empty($usuario->respuesta_seguridad_hash)) {
        Yii::debug("Usuario $id no tiene pregunta/respuesta configurada");
        Yii::$app->session->setFlash('error', 'No hay pregunta de seguridad configurada.');
        return $this->redirect(['usuario/recuperar-contrasena']);
    }

    $model = new \app\models\FormVerificarSeguridad(['scenario' => 'recuperarCuenta']);

    if ($model->load(Yii::$app->request->post())) {
        // 4. Depuración detallada
        Yii::debug("=== INICIO DE DEPURACIÓN ===");
        Yii::debug("Respuesta recibida: '" . $model->respuesta_seguridad . "'");
        Yii::debug("Longitud respuesta: " . strlen($model->respuesta_seguridad));
        Yii::debug("Usuario ID: " . $usuario->id);
        Yii::debug("Hash almacenado: " . $usuario->respuesta_seguridad_hash);
        
        // 5. Normalización de la respuesta
        $respuestaNormalizada = trim($model->respuesta_seguridad);
        Yii::debug("Respuesta normalizada: '" . $respuestaNormalizada . "'");
        
        // 6. Validación manual detallada
        $hashValido = !empty($usuario->respuesta_seguridad_hash) && 
                    strpos($usuario->respuesta_seguridad_hash, '$2y$') === 0;
        
        Yii::debug("Hash válido: " . ($hashValido ? 'Sí' : 'No'));
        
        if ($hashValido) {
            $validacion = Yii::$app->security->validatePassword($respuestaNormalizada, $usuario->respuesta_seguridad_hash);
            Yii::debug("Resultado validación: " . ($validacion ? 'ÉXITO' : 'FALLÓ'));
            
            // 7. Generar hash de prueba para comparación
            $hashDePrueba = Yii::$app->security->generatePasswordHash($respuestaNormalizada);
            Yii::debug("Hash generado ahora: " . $hashDePrueba);
            
            if ($validacion) {
                Yii::$app->session->set('usuario_recuperar', $usuario->id);
                return $this->redirect(['usuario/restablecer-contrasena']);
            } else {
                Yii::debug("Comparación fallida. Posibles causas:");
                Yii::debug("- La respuesta no coincide con la almacenada");
                Yii::debug("- El hash fue generado con diferentes opciones");
                Yii::debug("- Problemas de codificación de caracteres");
            }
        } else {
            Yii::debug("Hash inválido en la base de datos");
        }

        Yii::debug("=== FIN DE DEPURACIÓN ===");
        Yii::$app->session->setFlash('error', 'Respuesta incorrecta.');
    }

    return $this->render('verificar-seguridad', [
        'model' => $model,
        'user' => $usuario
    ]);
}

public function actionRestablecerContrasena()
{
    $id = Yii::$app->session->get('usuario_recuperar');
    if (!$id) {
        return $this->redirect(['index']);
    }

    $usuario = User::findOne($id);

    if (!$usuario) {
        Yii::$app->session->setFlash('error', 'Usuario no encontrado.');
        return $this->redirect(['index']);
    }

    if (Yii::$app->request->isPost) {
        $usuario->password = Yii::$app->request->post('password');
        $usuario->password_repeat = Yii::$app->request->post('password_repeat');

        if ($usuario->validate(['password', 'password_repeat'])) {
            $db= Yii::$app->db;
            $command= $db->createCommand("CALL sp_actualizar_contrasena (:_id_usuario,:_password,@_error)");
            $command->bindValue(':_id_usuario',$usuario->id);
            $command->bindValue(':_password',$usuario->password);
            $command->execute();
            $result = $db->createCommand("SELECT @_error AS error")->queryOne();   
            Yii::$app->session->remove('usuario_recuperar');
            Yii::$app->session->setFlash('success', 'Contraseña actualizada. Puedes iniciar sesión.');
            return $this->redirect(['index']);
        }
    }

    return $this->render('restablecer-contrasena', ['usuario' => $usuario]);
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
    public function actionCreateClientes()
{
    $model = new User();
    $model->scenario = 'create'; // Asegúrate que el escenario incluya los campos de seguridad

    if ($model->load(Yii::$app->request->post())) {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // 1. Ejecutar el procedimiento almacenado para crear el usuario
            $result = $this->CreateSP($model);
            
            if (!$result || $result['error'] != 0) {
                throw new \Exception('Error en el procedimiento almacenado. Código: ' . ($result['error'] ?? 'desconocido'));
            }

            // 2. Buscar el usuario recién creado
            $usuario = User::find()->where(['correo' => $model->correo])->orderBy(['id' => SORT_DESC])->one();
            
            if (!$usuario) {
                throw new \Exception('Usuario no encontrado después de creación');
            }

            // 3. Actualizar manualmente los campos de seguridad
            Yii::$app->db->createCommand()->update('usuario', [
                'pregunta_seguridad' => $model->pregunta_seguridad,
                'respuesta_seguridad_hash' => Yii::$app->security->generatePasswordHash($model->respuesta_seguridad_hash)
            ], ['id' => $usuario->id])->execute();

            $transaction->commit();
            
            Yii::$app->session->setFlash('success', 'Registro exitoso. Ahora puedes iniciar sesión.');
            return $this->redirect(['..\site\login']);
            
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error("Error al registrar cliente: " . $e->getMessage());
            Yii::$app->session->setFlash('error', 'Error al registrar: ' . $e->getMessage());
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

        if (!Yii::$app->user->isGuest && Yii::$app->user->identity->hasRole(1)) {
            return $this->render('create-staff', [
                'model' => $model,
                'roles'=>$roles,
            ]);
        } else {
            return $this->render('..\site\index');
        }
    }
    


    public function actionAsignarHorario(){
        if (!Yii::$app->user->isGuest && Yii::$app->user->identity->hasRole(1)) {
            return $this->render('asignar-horario');
        } else {
            return $this->render('..\site\index');
        }

    }

    public function actionGuardarHorario()
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    if (Yii::$app->request->isPost) {
        $usuario_id = Yii::$app->request->post('User')['id'];
        $horario_id = Yii::$app->request->post('Horario')['id'];

        if (!$usuario_id || !$horario_id) {
            return ['success' => false, 'message' => 'Debe seleccionar un operador y un horario.'];
        }

        $db = Yii::$app->db;

        // Desactivar el horario anterior del usuario
        $db->createCommand()
            ->update('operador_horario', ['status' => '0'], 'usuario_id = :usuario_id AND status = "1"')
            ->bindValue(':usuario_id', $usuario_id)
            ->execute();

        // Verificar si el usuario ya tiene el mismo horario asignado
        $existe = (new \yii\db\Query())
            ->select('id')
            ->from('operador_horario')
            ->where(['usuario_id' => $usuario_id, 'horario_id' => $horario_id])
            ->scalar();

        if ($existe) {
            // Si ya existe, actualizar a activo
            $db->createCommand()
                ->update('operador_horario', ['status' => '1'], 'id = :id')
                ->bindValue(':id', $existe)
                ->execute();
        } else {
            // Si no existe, insertar el nuevo horario
            $db->createCommand()
                ->insert('operador_horario', [
                    'usuario_id' => $usuario_id,
                    'horario_id' => $horario_id,
                    'status' => '1'
                ])
                ->execute();
        }

        Yii::$app->session->setFlash('success', 'Horario asignado correctamente');
        return $this->redirect(['usuario/asignar-horario']);
    }

    return ['success' => false, 'message' => 'Método no permitido.'];
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
        $operador = $this->findModel($id);
    
        // Marcar como inactivo
        $operador->setStatusToInactivo();
    
        // Buscar tickets abiertos del operador actual
        $ticketsAbiertos = Tickets::find()
            ->where(['id_operador' => $operador->id, 'estado' => 'abierto'])
            ->all();
    
        if (!empty($ticketsAbiertos)) {
            // Buscar operadores activos distintos al que se está deshabilitando
            $operadoresActivos = User::find()
                ->where(['status' => 'activo','id_rol' => 2])
                ->andWhere(['<>', 'id', $operador->id])
                ->all();
    
            $minTickets = PHP_INT_MAX;
            $operadorConMenos = null;
    
            foreach ($operadoresActivos as $op) {
                $cantidad = Tickets::find()
                    ->where(['id_operador' => $op->id, 'estado' => 'abierto'])
                    ->count();
    
                if ($cantidad < $minTickets) {
                    $minTickets = $cantidad;
                    $operadorConMenos = $op;
                }
            }
    
            if ($operadorConMenos) {
                foreach ($ticketsAbiertos as $ticket) {
                    $ticket->id_operador = $operadorConMenos->id;
                    $ticket->save(false);
                }
    
                Yii::$app->session->setFlash('success', 'Miembro del staff inhabilitado y tickets reasignados a ' . Html::encode($operadorConMenos->nombre));
            } else {
                Yii::$app->session->setFlash('warning', 'Miembro del staff inhabilitado, pero no se encontraron operadores activos para reasignar los tickets.');
            }
        } else {
            Yii::$app->session->setFlash('info', 'Miembro del staff inhabilitado. No tenía tickets abiertos.');
        }
    
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

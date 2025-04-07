<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;


/**
 * This is the model class for table "User".
 *
 * @property int $id
 * @property int $id_rol
 * @property string $nombre
 * @property string $apellido_paterno
 * @property string $apellido_materno
 * @property string $correo
 * @property string $password
 * @property string $salt
 * @property string $status
 *
 * @property Asistencia[] $asistencias
 * @property CalificacionSoporte[] $calificacionSoportes
 * @property Calificacion[] $calificacions
 * @property Mensajes[] $mensajes
 * @property Rol $rol
 * @property Tickets[] $tickets
 * @property Tickets[] $tickets0
 * @property UserServicio[] $UserServicios
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{

    /**
     * ENUM field values
     */
    public $password_repeat;

    public $currentPassword;
    public $newPassword;
    public $newPasswordRepeat;
    const STATUS_ACTIVO = 'activo';
    const STATUS_INACTIVO = 'inactivo';


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuario';
    }
    public function scenarios()
{
    return [
        'default' => ['id_rol', 'nombre', 'apellido_paterno', 'apellido_materno', 'correo', 'password', 'password_repeat', 'status'],
'create' => ['id_rol', 'nombre', 'apellido_paterno', 'apellido_materno', 'correo', 'password', 'password_repeat', 'status', 'pregunta_seguridad', 'respuesta_seguridad_hash'],
        'changePassword' => ['currentPassword', 'newPassword', 'newPasswordRepeat'],
        'recuperarCuenta' => ['pregunta_seguridad', 'respuesta_seguridad', 'respuesta_seguridad_hash'],
    ];
}

    /**
     * {@inheritdoc}
     */
    public function rules()
{
    return [
        // Reglas generales (aplican a todos los scenarios)
        [['status'], 'default', 'value' => 'activo'],
        [['id_rol', 'nombre', 'apellido_paterno', 'apellido_materno', 'correo', 'password'], 'required'],
        [['id_rol'], 'integer'],
        [['status'], 'string'],
        [['nombre', 'apellido_paterno', 'apellido_materno'], 'string', 'max' => 70],
        [['correo'], 'string', 'max' => 60],
        [['password'], 'string', 'min' => 8, 'max' => 350],
        //Reglas para la recupercacion de cuenta
        [['pregunta_seguridad', 'respuesta_seguridad'], 'string'],
        [['pregunta_seguridad', 'respuesta_seguridad'], 'safe'],
        [['correo'], 'unique', 'targetClass' => User::class, 'message' => 'Este correo ya está registrado.'],
        [['correo'], 'email', 'message' => 'El formato del correo no es válido.'],
        [['pregunta_seguridad'],'string','max'=>255],
        [['respuesta_seguridad_hash'],'string','max'=>255],

        [['password_repeat'], 'string', 'min' => 8, 'max' => 350],
        [['password_repeat'], 'compare', 'compareAttribute' => 'password', 'message' => 'Las contraseñas no coinciden'],
        [['salt'], 'string', 'max' => 50],
        ['status', 'in', 'range' => array_keys(self::optsStatus())],
        [['id_rol'], 'exist', 'skipOnError' => true, 'targetClass' => Rol::class, 'targetAttribute' => ['id_rol' => 'id']],
        
        // Reglas específicas para cambio de contraseña
        [['currentPassword', 'newPassword', 'newPasswordRepeat'], 'required'],
        ['newPassword', 'string', 'min' => 8],
        ['newPasswordRepeat', 'compare', 'compareAttribute' => 'newPassword'],
    ];
}


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Usuario',
            'id_rol' => 'Rol',
            'nombre' => 'Nombre',
            'apellido_paterno' => 'Apellido Paterno',
            'apellido_materno' => 'Apellido Materno',
            'correo' => 'Correo',
            'password' => 'Contraseña',
            'pregunta_seguridad'=>'Pregunta de Seguridad',
            'respuesta_seguridad_hash'=>'Respuesta',
            'currentPassword' => 'Contraseña Actual',
            'newPassword' => 'Nueva Contraseña',
            'newPasswordRepeat' => 'Repetir Nueva Contraseña',
            'password_repeat' => 'Repetir Contraseña',
            'salt' => 'Salt',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[Asistencias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAsistencias()
    {
        return $this->hasMany(Asistencia::class, ['User_id' => 'id']);
    }
    public function validateCurrentPassword($attribute, $params)
{
    if (!$this->hasErrors()) {
        if (!$this->validatePassword($this->correo, $this->currentPassword)) {
            $this->addError($attribute, 'La contraseña actual es incorrecta.');
        }
    }
}
public function getNombreCompleto()
{
    return trim($this->nombre . ' ' . $this->apellido_paterno . ' ' . $this->apellido_materno);
}

    /**
     * Gets query for [[CalificacionSoportes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCalificacionSoportes()
    {
        return $this->hasMany(CalificacionSoporte::class, ['id_operador' => 'id']);
    }

    /**
     * Gets query for [[Calificacions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCalificacions()
    {
        return $this->hasMany(Calificacion::class, ['id_User' => 'id']);
    }

    /**
     * Gets query for [[Mensajes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMensajes()
    {
        return $this->hasMany(Mensajes::class, ['id_User' => 'id']);
    }

    /**
     * Gets query for [[Rol]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRol()
    {
        return $this->hasOne(Rol::class, ['id' => 'id_rol']);
    }

    /**
     * Gets query for [[Tickets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTickets()
    {
        return $this->hasMany(Tickets::class, ['id_cliente' => 'id']);
    }

    public function getTicketsAQ(){

        $query= $this->hasMany(Tickets::class,['id_cliente'=>'id']);
        return new ActiveDataProvider([
            'query'=>$query,
            'pagination'=>[
                'pageSize'=>10,
            ]
    ]);
    }

    public function getHorarioActivo()
{
    return $this->hasOne(OperadorHorario::class, ['usuario_id' => 'id'])
        ->where(['status' => '1']) // Solo el horario activo
        ->joinWith('horario'); // Unir con la tabla `horario`
}


    /**
     * Gets query for [[Tickets0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTickets0()
    {
        return $this->hasMany(Tickets::class, ['id_operador' => 'id']);
    }
    
    public function getTickets0AQ()
    {
        $query= $this->hasMany(Tickets::class,['id_operador'=>'id']);
        return new ActiveDataProvider([
            'query'=>$query,
            'pagination'=>[
                'pageSize'=>10,
            ]
            ]);
    }
    /**
     * Gets query for [[UserServicios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserServicios()
    {
        return $this->hasMany(UserServicio::class, ['id_User' => 'id']);
    }


    /**
     * column status ENUM value labels
     * @return string[]
     */
    public static function optsStatus()
    {
        return [
            self::STATUS_ACTIVO => 'activo',
            self::STATUS_INACTIVO => 'inactivo',
        ];
    }

    /**
     * @return string
     */
    public function displayStatus()
    {
        return self::optsStatus()[$this->status];
    }

    /**
     * @return bool
     */
    public function isStatusActivo()
    {
        return $this->status === self::STATUS_ACTIVO;
    }

    public function setStatusToActivo()
    {
        $this->status = self::STATUS_ACTIVO;
    }

    /**
     * @return bool
     */
    public function isStatusInactivo()
    {
        return $this->status === self::STATUS_INACTIVO;
    }

    public function setStatusToInactivo()
    {
        $this->status = self::STATUS_INACTIVO;
        return $this->save(false);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByEmail($correo)
    {
        return self:: findOne(['correo'=>$correo]);
    }

    public static function findByCorreo($correo)
    {
        return static::findOne(['correo' => $correo]);
    }

    /** 
     * Bloque de funciones para poder recuperar una cuenta de la cual no tenemos contraseña
    */

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
        $this->save(false);
    }
    
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }
        
        return static::findOne([
            'password_reset_token' => $token,
        ]);
    }
    
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        
        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = 3600; // 1 hora de validez
        return $timestamp + $expire >= time();
    }
    
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
        $this->save(false);
    }

    public function setRespuestaSeguridad($respuesta) {
        $this->respuesta_seguridad_hash = Yii::$app->security->generatePasswordHash($respuesta);
    }
    
    public function validarRespuestaSeguridad($respuesta)
{
    if (empty($respuesta) || empty($this->respuesta_seguridad_hash)) {
        return false;
    }

    return Yii::$app->security->validatePassword($respuesta, $this->respuesta_seguridad_hash);
}



    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    public function getPreguntaSec(){
        return $this->pregunta_seguridad;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return null;
    }

    public function hasRole($role){

        return $this->id_rol===$role;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }
    
    public function validatePassword($correo, $password)
{
    $command = Yii::$app->db->createCommand('CALL validate_user_password(:correo, :password, @result)');
    $command->bindValue(':correo', $correo);
    $command->bindValue(':password', $password);
    $command->execute();

    // Obtener el resultado
    $result = Yii::$app->db->createCommand('SELECT @result AS result')->queryScalar();

    if ($result == 1) {
        return true; // Contraseña correcta
    } else {
        return false; // Contraseña incorrecta
    }
}

// Removed duplicate validateCurrentPassword method to avoid redeclaration error.



    


    public function debugPasswordInfo($inputPassword)
{
    return [
        'stored_password' => $this->password,
        'stored_salt' => $this->salt,
        'input_hashed' => hash('sha256', $inputPassword),
        'salt_from_password' => substr($this->password, 64),
        'password_length' => strlen($this->password),
        'validation_result' => $this->validatePassword($inputPassword)
    ];
}

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    /**
 * Cambia la contraseña verificando la actual primero
 * 
 * @param string $currentPassword Contraseña actual en texto plano
 * @param string $newPassword Nueva contraseña en texto plano
 * @return bool Si el cambio fue exitoso
 */
public function changePassword($currentPassword, $newPassword)
{
    if ($this->newPassword !== $this->newPasswordRepeat) {
        $this->addError('newPasswordRepeat', 'Las contraseñas no coinciden');
        return false;
    }
    $command = Yii::$app->db->createCommand('CALL update_user_password(:correo, :currentPassword, :newPassword)');
    $command->bindValue(':correo', $this->correo);
    $command->bindValue(':currentPassword', $currentPassword);
    $command->bindValue(':newPassword', $newPassword);

    Yii::info("Ejecutando procedimiento almacenado con correo: {$this->correo}", __METHOD__);
    Yii::info("Contraseña actual ingresada: $currentPassword", __METHOD__);
    Yii::info("Nueva contraseña a actualizar: $newPassword", __METHOD__);

    $result = $command->queryScalar(); // Devuelve el resultado del procedimiento almacenado

    Yii::info("Resultado del procedimiento: $result", __METHOD__);

    if ($result == 1) {
        return true; // Contraseña actualizada con éxito
    } elseif ($result == 2) {
        $this->addError('currentPassword', 'La contraseña actual es incorrecta');
        return false;
    } else {
        $this->addError('currentPassword', 'Error al actualizar la contraseña');
        return false;
    }
}








    /**
 * Actualiza la contraseña de un usuario manteniendo el formato de encriptación existente
 * 
 * @param string $newPassword Nueva contraseña en texto plano
 * @return bool Si la actualización fue exitosa
 */
public function updatePassword($newPassword)
{
    // Generar un nuevo salt (puedes mantener el mismo si prefieres)
    $salt = Yii::$app->security->generateRandomString();
    
    // Encriptar según tu formato actual: SHA-256 + salt concatenado
    $encryptedPassword = hash('sha256', $newPassword) . $salt;
    
    // Actualizar los campos
    $this->password = $encryptedPassword;
    $this->salt = $salt;
    
    return $this->save(false); // false para saltear validación si no es necesaria
}



    public function info(){

        return $this->nombre.'-'.$this->rol->nombre;
    }

    public function rolname(){

        $this->rol->nombre;
    }

    

    public function validateAccess($seccion){
        $idRol= $this->id_rol;
        return (new \yii\db\Query())
        ->from('permisos')
        ->innerJoin('seccion','permisos.id_seccion=seccion.id')
        ->where(['permisos.id_rol'=>$idRol,'seccion.nombre'=>$seccion,'permisos.estatus'=>'activo'])
        ->exists();
    }

    public static function registrarAsistencia($id_usuario){
        $connection=Yii::$app->db;
        $command=$connection->createCommand('CALL registrarAsistencia(:id_usuario, @error)');
        $command->bindValue(':id_usuario',$id_usuario);
        return $command->queryAll();

    }
    
    public static function registrarSalida($id_usuario){
        $connection= Yii::$app->db;
        $command=$connection->createCommand('CALL registrarSalida(:id_usuario, @error)');
        $command->bindValue(':id_usuario',$id_usuario);
        return $command->queryAll();
    }
    

    
    
}

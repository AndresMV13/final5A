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
    const STATUS_ACTIVO = 'activo';
    const STATUS_INACTIVO = 'inactivo';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'default', 'value' => 'activo'],
            [['id_rol', 'nombre', 'apellido_paterno', 'apellido_materno', 'correo', 'password'], 'required'],
            [['id_rol'], 'integer'],
            [['status'], 'string'],
            [['nombre', 'apellido_paterno', 'apellido_materno'], 'string', 'max' => 70],
            [['correo'], 'string', 'max' => 60],
            [['password'], 'string', 'max' => 350],
            [['salt'], 'string', 'max' => 50],
            ['status', 'in', 'range' => array_keys(self::optsStatus())],
            [['id_rol'], 'exist', 'skipOnError' => true, 'targetClass' => Rol::class, 'targetAttribute' => ['id_rol' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_rol' => 'Rol',
            'nombre' => 'Nombre',
            'apellido_paterno' => 'Apellido Paterno',
            'apellido_materno' => 'Apellido Materno',
            'correo' => 'Correo',
            'password' => 'Password',
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

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
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

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        $storedHash=$this->password;
        $salt=$this->salt;

        $hashedPassword = hash('sha256',$password,$salt);

        return $this->password === $password;
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

<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
/**
 * This is the model class for table "tickets".
 *
 * @property int $id
 * @property int $id_cliente
 * @property int $id_operador
 * @property int $id_servicio
 * @property string $n_serie
 * @property string|null $descripcion
 * @property string $fecha_creacion
 * @property string|null $estado
 *
 * @property Usuario $cliente
 * @property Mensajes[] $mensajes
 * @property Usuario $operador
 * @property Servicio $servicio
 */
class Tickets extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const ESTADO_ABIERTO = 'abierto';
    const ESTADO_EN_PROGRESO = 'en progreso';
    const ESTADO_CERRADO = 'cerrado';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tickets';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion'], 'default', 'value' => null],
            [['estado'], 'default', 'value' => 'abierto'],
            [['id_cliente', 'id_operador', 'id_servicio', 'n_serie'], 'required'],
            [['id_cliente', 'id_operador', 'id_servicio'], 'integer'],
            [['descripcion', 'estado'], 'string'],
            [['fecha_creacion'], 'safe'],
            [['n_serie'], 'string', 'max' => 255],
            ['estado', 'in', 'range' => array_keys(self::optsEstado())],
            [['id_cliente'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_cliente' => 'id']],
            [['id_operador'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_operador' => 'id']],
            [['id_servicio'], 'exist', 'skipOnError' => true, 'targetClass' => Servicio::class, 'targetAttribute' => ['id_servicio' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_cliente' => 'Id Cliente',
            'id_operador' => 'Id Operador',
            'id_servicio' => 'Servicio',
            'n_serie' => 'N Serie',
            'descripcion' => 'Descripcion',
            'fecha_creacion' => 'Fecha Creacion',
            'estado' => 'Estado',
        ];
    }

    /**
     * Gets query for [[Cliente]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(User::class, ['id' => 'id_cliente']);
    }

    /**
     * Gets query for [[Mensajes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMensajes()
    {
        return $this->hasMany(Mensajes::class, ['id_ticket' => 'id']);
    }

    public function getLastMessage()
    {
        return Mensajes::find()
        ->select(['mensajes.fecha','usuario.id_rol'])
        ->innerJoin('usuario','mensajes.id_usuario = usuario.id')
        ->where(['mensajes.id_ticket'=>$this->id])
        ->orderBy(['mensajes.fecha'=>SORT_DESC])
        ->limit(1)
        ->asArray()
        ->one();
        if (!$lastMessage) {
            return null; // O un arreglo vacío, dependiendo de cómo prefieras manejarlo
        }
    
        return $lastMessage;
    }

    /**
     * Gets query for [[Operador]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOperador()
    {
        return $this->hasOne(User::class, ['id' => 'id_operador']);
    }

    
    public function getNombreOperador(){
        return $this->operador->nombre;
    }
    
    public function getNombreCliente(){
        return $this->cliente->nombre;
    }

    /**
     * Gets query for [[Servicio]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getServicio()
    {
        return $this->hasOne(Servicio::class, ['id' => 'id_servicio']);
    }

    public function getNombreServicio(){
        return $this->servicio->nombre;
    }


    /**
     * column estado ENUM value labels
     * @return string[]
     */
    public static function optsEstado()
    {
        return [
            self::ESTADO_ABIERTO => 'abierto',
            self::ESTADO_EN_PROGRESO => 'en progreso',
            self::ESTADO_CERRADO => 'cerrado',
        ];
    }

    /**
     * @return string
     */
    public function displayEstado()
    {
        return self::optsEstado()[$this->estado];
    }

    /**
     * @return bool
     */
    public function isEstadoAbierto()
    {
        return $this->estado === self::ESTADO_ABIERTO;
    }

    public function setEstadoToAbierto()
    {
        $this->estado = self::ESTADO_ABIERTO;
    }

    /**
     * @return bool
     */
    public function isEstadoEnProgreso()
    {
        return $this->estado === self::ESTADO_EN_PROGRESO;
    }

    public function setEstadoToEnProgreso()
    {
        $this->estado = self::ESTADO_EN_PROGRESO;
    }

    /**
     * @return bool
     */
    public function isEstadoCerrado()
    {
        return $this->estado === self::ESTADO_CERRADO;
    }

    public function setEstadoToCerrado()
    {
        $this->estado = self::ESTADO_CERRADO;
    }
}

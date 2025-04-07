<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usuario_servicio".
 *
 * @property int $id
 * @property int|null $id_usuario
 * @property int|null $id_servicio
 * @property string $estatus
 *
 * @property Servicio $servicio
 * @property User $usuario
 */
class UsuarioServicio extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const ESTATUS_ACTIVO = 'activo';
    const ESTATUS_INACTIVO = 'inactivo';
    const ESTATUS_PENDIENTE = 'pendiente';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuario_servicio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_usuario', 'id_servicio'], 'default', 'value' => null],
            [['estatus'], 'default', 'value' => 'activo'],
            [['id_usuario', 'id_servicio'], 'integer'],
            [['estatus'], 'string'],
            ['estatus', 'in', 'range' => array_keys(self::optsEstatus())],
            [['fecha_contratacion', 'fecha_cancelacion'], 'safe'],
            [['precio_contradado'], 'number'],
            [['id_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_usuario' => 'id']],
            [['id_servicio'], 'exist', 'skipOnError' => true, 'targetClass' => Servicio::class, 'targetAttribute' => ['id_servicio' => 'id']],
            ['id_servicio', 'validateServicioUnico', 'skipOnError' => true],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_usuario' => 'Id Usuario',
            'id_servicio' => 'Id Servicio',
            'estatus' => 'Estatus',
        ];
    }

    public function validateServicioUnico($attribute, $params)
{
    $existe = self::find()
        ->where(['id_usuario' => $this->id_usuario, 'id_servicio' => $this->id_servicio])
        ->andWhere(['in', 'estatus', ['activo', 'pendiente']])
        ->exists();

    if ($existe) {
        $this->addError($attribute, 'Ya tienes este servicio activo o con una solicitud pendiente.');
    }
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

    /**
     * Gets query for [[Usuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(User::class, ['id' => 'id_usuario']);
    }


    /**
     * column estatus ENUM value labels
     * @return string[]
     */
    public static function optsEstatus()
    {
        return [
            self::ESTATUS_ACTIVO => 'activo',
            self::ESTATUS_INACTIVO => 'inactivo',
            self::ESTATUS_PENDIENTE => 'pendiente',
        ];
    }

    /**
     * @return string
     */
    public function displayEstatus()
    {
        return self::optsEstatus()[$this->estatus];
    }

    /**
     * @return bool
     */
    public function isEstatusActivo()
    {
        return $this->estatus === self::ESTATUS_ACTIVO;
    }

    public function setEstatusToActivo()
    {
        $this->estatus = self::ESTATUS_ACTIVO;
    }

    /**
     * @return bool
     */
    public function isEstatusInactivo()
    {
        return $this->estatus === self::ESTATUS_INACTIVO;
    }

    public function setEstatusToInactivo()
    {
        $this->estatus = self::ESTATUS_INACTIVO;
    }

    
}

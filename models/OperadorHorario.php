<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "operador_horario".
 *
 * @property int $id
 * @property int $usuario_id
 * @property int $horario_id
 * @property string $fecha_asignacion
 * @property string $status
 *
 * @property Horario $horario
 * @property Usuario $usuario
 */
class OperadorHorario extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const STATUS_0 = '0';
    const STATUS_1 = '1';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'operador_horario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['usuario_id', 'horario_id', 'status'], 'required'],
            [['usuario_id', 'horario_id'], 'integer'],
            [['fecha_asignacion'], 'safe'],
            [['status'], 'string'],
            ['status', 'in', 'range' => array_keys(self::optsStatus())],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['usuario_id' => 'id']],
            [['horario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Horario::class, 'targetAttribute' => ['horario_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'usuario_id' => 'Usuario ID',
            'horario_id' => 'Horario ID',
            'fecha_asignacion' => 'Fecha Asignacion',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[Horario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHorario()
    {
        return $this->hasOne(Horario::class, ['id' => 'horario_id']);
    }

    /**
     * Gets query for [[Usuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(User::class, ['id' => 'usuario_id']);
    }


    /**
     * column status ENUM value labels
     * @return string[]
     */
    public static function optsStatus()
    {
        return [
            self::STATUS_0 => '0',
            self::STATUS_1 => '1',
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
    public function isStatus0()
    {
        return $this->status === self::STATUS_0;
    }

    public function setStatusTo0()
    {
        $this->status = self::STATUS_0;
    }

    /**
     * @return bool
     */
    public function isStatus1()
    {
        return $this->status === self::STATUS_1;
    }

    public function setStatusTo1()
    {
        $this->status = self::STATUS_1;
    }
}

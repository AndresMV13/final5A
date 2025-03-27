<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mensajes".
 *
 * @property int $id
 * @property int $id_ticket
 * @property int $id_usuario
 * @property string $mensaje
 * @property string $fecha
 *
 * @property Tickets $ticket
 * @property Usuario $usuario
 */
class Mensajes extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mensajes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_ticket', 'id_usuario', 'mensaje'], 'required'],
            [['id_ticket', 'id_usuario'], 'integer'],
            [['mensaje'], 'string'],
            [['fecha'], 'safe'],
            [['id_ticket'], 'exist', 'skipOnError' => true, 'targetClass' => Tickets::class, 'targetAttribute' => ['id_ticket' => 'id']],
            [['id_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_usuario' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_ticket' => 'Id Ticket',
            'id_usuario' => 'Id Usuario',
            'mensaje' => 'Mensaje',
            'fecha' => 'Fecha',
        ];
    }

    /**
     * Gets query for [[Ticket]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTicket()
    {
        return $this->hasOne(Tickets::class, ['id' => 'id_ticket']);
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

}

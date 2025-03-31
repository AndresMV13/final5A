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
 *
 * @property Horario $horario
 * @property Usuario $usuario
 */
class OperadorHorario extends \yii\db\ActiveRecord
{


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
            [['usuario_id', 'horario_id'], 'required'],
            [['usuario_id', 'horario_id'], 'integer'],
            [['fecha_asignacion'], 'safe'],
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
            'usuario_id' => 'Operador',
            'horario_id' => 'Horario',
            'fecha_asignacion' => 'Fecha Asignacion',
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

}

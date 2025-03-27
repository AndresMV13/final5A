<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "respaldo".
 *
 * @property int $id
 * @property int $id_usuario
 * @property string $fecha
 *
 * @property Usuario $usuario
 */
class Respaldo extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'respaldo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_usuario'], 'required'],
            [['id', 'id_usuario'], 'integer'],
            [['fecha'], 'safe'],
            [['id'], 'unique'],
            [['id_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::class, 'targetAttribute' => ['id_usuario' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_usuario' => 'Usuario que realizo el respaldo',
            'fecha' => 'Fecha en la que se realizo el respaldo',
        ];
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

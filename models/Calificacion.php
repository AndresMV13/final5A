<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "calificacion".
 *
 * @property int $id
 * @property int|null $id_usuario
 * @property int|null $id_servicio
 * @property string|null $calificacion
 *
 * @property Servicio $servicio
 * @property Usuario $usuario
 */
class Calificacion extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const CALIFICACION_1 = '1';
    const CALIFICACION_2 = '2';
    const CALIFICACION_3 = '3';
    const CALIFICACION_4 = '4';
    const CALIFICACION_5 = '5';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'calificacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_usuario', 'id_servicio', 'calificacion'], 'default', 'value' => null],
            [['id', 'id_usuario', 'id_servicio'], 'integer'],
            [['calificacion'], 'string'],
            ['calificacion', 'in', 'range' => array_keys(self::optsCalificacion())],
            [['id_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_usuario' => 'id']],
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
            'id_usuario' => 'Id Usuario',
            'id_servicio' => 'Servicio',
            'calificacion' => 'Calificacion',
        ];
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
     * column calificacion ENUM value labels
     * @return string[]
     */
    public static function optsCalificacion()
    {
        return [
            self::CALIFICACION_1 => '1',
            self::CALIFICACION_2 => '2',
            self::CALIFICACION_3 => '3',
            self::CALIFICACION_4 => '4',
            self::CALIFICACION_5 => '5',
        ];
    }

    /**
     * @return string
     */
    public function displayCalificacion()
    {
        return self::optsCalificacion()[$this->calificacion];
    }

    /**
     * @return bool
     */
    public function isCalificacion1()
    {
        return $this->calificacion === self::CALIFICACION_1;
    }

    public function setCalificacionTo1()
    {
        $this->calificacion = self::CALIFICACION_1;
    }

    /**
     * @return bool
     */
    public function isCalificacion2()
    {
        return $this->calificacion === self::CALIFICACION_2;
    }

    public function setCalificacionTo2()
    {
        $this->calificacion = self::CALIFICACION_2;
    }

    /**
     * @return bool
     */
    public function isCalificacion3()
    {
        return $this->calificacion === self::CALIFICACION_3;
    }

    public function setCalificacionTo3()
    {
        $this->calificacion = self::CALIFICACION_3;
    }

    /**
     * @return bool
     */
    public function isCalificacion4()
    {
        return $this->calificacion === self::CALIFICACION_4;
    }

    public function setCalificacionTo4()
    {
        $this->calificacion = self::CALIFICACION_4;
    }

    /**
     * @return bool
     */
    public function isCalificacion5()
    {
        return $this->calificacion === self::CALIFICACION_5;
    }

    public function setCalificacionTo5()
    {
        $this->calificacion = self::CALIFICACION_5;
    }
}

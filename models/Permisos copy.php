<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "permisos".
 *
 * @property int $id
 * @property int $id_rol
 * @property int $id_seccion
 * @property string $estatus
 *
 * @property Rol $rol
 * @property Seccion $seccion
 */
class Permisos extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const ESTATUS_ACTIVO = 'activo';
    const ESTATUS_INACTIVO = 'inactivo';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'permisos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_rol', 'id_seccion', 'estatus'], 'required'],
            [['id_rol', 'id_seccion'], 'integer'],
            [['estatus'], 'string'],
            ['estatus', 'in', 'range' => array_keys(self::optsEstatus())],
            [['id_rol'], 'exist', 'skipOnError' => true, 'targetClass' => Rol::class, 'targetAttribute' => ['id_rol' => 'id']],
            [['id_seccion'], 'exist', 'skipOnError' => true, 'targetClass' => Seccion::class, 'targetAttribute' => ['id_seccion' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_rol' => 'Id Rol',
            'id_seccion' => 'Id Seccion',
            'estatus' => 'Estatus',
        ];
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
     * Gets query for [[Seccion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSeccion()
    {
        return $this->hasOne(Seccion::class, ['id' => 'id_seccion']);
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

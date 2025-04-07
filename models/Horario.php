<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "horario".
 *
 * @property int $id
 * @property string $dias
 * @property string $turno
 * @property string $hora_entrada_esperada
 * @property string $hora_salida_esperada
 *
 * @property CambiosHorarios[] $cambiosHorarios
 * @property CambiosHorarios[] $cambiosHorarios0
 * @property OperadorAsistencia[] $operadorAsistencias
 * @property OperadorHorario[] $operadorHorarios
 */
class Horario extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const DIAS_LUNES_VIERNES = 'lunes-viernes';
    const DIAS_SABADO_DOMINGO = 'sabado-domingo';
    const TURNO_MATUTINO = 'matutino';
    const TURNO_VESPERTINO = 'vespertino';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'horario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dias', 'turno', 'hora_entrada_esperada', 'hora_salida_esperada'], 'required'],
            [['dias', 'turno'], 'string'],
            [['hora_entrada_esperada', 'hora_salida_esperada'], 'safe'],
            ['dias', 'in', 'range' => array_keys(self::optsDias())],
            ['turno', 'in', 'range' => array_keys(self::optsTurno())],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Horario',
            'dias' => 'Dias',
            'turno' => 'Turno',
            'hora_entrada_esperada' => 'Hora Entrada Esperada',
            'hora_salida_esperada' => 'Hora Salida Esperada',
        ];
    }

    /**
     * Gets query for [[CambiosHorarios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCambiosHorarios()
    {
        return $this->hasMany(CambiosHorarios::class, ['horario_anterior_id' => 'id']);
    }

    /**
     * Gets query for [[CambiosHorarios0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCambiosHorarios0()
    {
        return $this->hasMany(CambiosHorarios::class, ['horario_nuevo_id' => 'id']);
    }

    /**
     * Gets query for [[OperadorAsistencias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOperadorAsistencias()
    {
        return $this->hasMany(OperadorAsistencia::class, ['horario_id' => 'id']);
    }

    /**
     * Gets query for [[OperadorHorarios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOperadorHorarios()
    {
        return $this->hasMany(OperadorHorario::class, ['horario_id' => 'id']);
    }


    /**
     * column dias ENUM value labels
     * @return string[]
     */
    public static function optsDias()
    {
        return [
            self::DIAS_LUNES_VIERNES => 'lunes-viernes',
            self::DIAS_SABADO_DOMINGO => 'sabado-domingo',
        ];
    }

    /**
     * column turno ENUM value labels
     * @return string[]
     */
    public static function optsTurno()
    {
        return [
            self::TURNO_MATUTINO => 'matutino',
            self::TURNO_VESPERTINO => 'vespertino',
        ];
    }

    /**
     * @return string
     */
    public function displayDias()
    {
        return self::optsDias()[$this->dias];
    }

    /**
     * @return bool
     */
    public function isDiasLunesViernes()
    {
        return $this->dias === self::DIAS_LUNES_VIERNES;
    }

    public function setDiasToLunesViernes()
    {
        $this->dias = self::DIAS_LUNES_VIERNES;
    }

    /**
     * @return bool
     */
    public function isDiasSabadoDomingo()
    {
        return $this->dias === self::DIAS_SABADO_DOMINGO;
    }

    public function setDiasToSabadoDomingo()
    {
        $this->dias = self::DIAS_SABADO_DOMINGO;
    }

    /**
     * @return string
     */
    public function displayTurno()
    {
        return self::optsTurno()[$this->turno];
    }

    /**
     * @return bool
     */
    public function isTurnoMatutino()
    {
        return $this->turno === self::TURNO_MATUTINO;
    }

    public function setTurnoToMatutino()
    {
        $this->turno = self::TURNO_MATUTINO;
    }

    /**
     * @return bool
     */
    public function isTurnoVespertino()
    {
        return $this->turno === self::TURNO_VESPERTINO;
    }

    public function setTurnoToVespertino()
    {
        $this->turno = self::TURNO_VESPERTINO;
    }

    public function getOperadoresActivos()
{
    return $this->hasMany(OperadorHorario::className(), ['horario_id' => 'id'])
        ->where(['status' => '1'])
        ->with('usuario')
        ->orderBy('fecha_asignacion DESC');
}
}

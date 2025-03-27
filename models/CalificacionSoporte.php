<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "calificacion_soporte".
 *
 * @property int $id
 * @property int $p1
 * @property int $p2
 * @property int $p3
 * @property int $p4
 * @property int $p5
 * @property int $id_operador
 * @property string $numero_serie
 *
 * @property Usuario $operador
 */
class CalificacionSoporte extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'calificacion_soporte';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['p1', 'p2', 'p3', 'p4', 'p5', 'id_operador', 'numero_serie'], 'required'],
            [['p1', 'p2', 'p3', 'p4', 'p5', 'id_operador'], 'integer'],
            [['numero_serie'], 'string'],
            [['id_operador'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_operador' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'p1' => '¿Cómo calificas la atención del operador?',
            'p2' => '¿Qué tal la rapidez en la respuesta?',
            'p3' => '¿Cómo evaluarías la solución del problema?',
            'p4' => '¿Fue claro el operador en sus explicaciones?',
            'p5' => '¿Recomendarías este operador?',
            'id_operador' => 'Id Operador',
            'numero_serie' => 'Numero Serie',
        ];
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

}

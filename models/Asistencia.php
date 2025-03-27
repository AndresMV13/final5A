<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
/**
 * This is the model class for table "asistencia".
 *
 * @property int $id
 * @property int $usuario_id
 * @property string $entrada
 * @property string|null $salida
 * @property string $nombre
 * @property string $apellido_paterno
 * @property string $apellido_materno
 *
 * @property Usuario $usuario
 */
class Asistencia extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'asistencia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['salida'], 'default', 'value' => null],
            [['usuario_id', 'entrada', 'nombre', 'apellido_paterno', 'apellido_materno'], 'required'],
            [['usuario_id'], 'integer'],
            [['entrada', 'salida'], 'safe'],
            [['nombre', 'apellido_paterno', 'apellido_materno'], 'string', 'max' => 100],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::class, 'targetAttribute' => ['usuario_id' => 'id']],
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
            'entrada' => 'Entrada',
            'salida' => 'Salida',
            'nombre' => 'Nombre',
            'apellido_paterno' => 'Apellido Paterno',
            'apellido_materno' => 'Apellido Materno',
        ];
    }

    /**
     * Gets query for [[Usuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuario::class, ['id' => 'usuario_id']);
    }

    public static function asistenciasCorreo(){
        $query= (new \yii\db\Query())
                ->select('asistencia.nombre, asistencia.apellido_paterno, correo, entrada, salida')
                ->from ('asistencia')
                ->innerJoin('usuario','usuario.id = asistencia.usuario_id');

        return new ActiveDataProvider([
            'query'=>$query,
            'pagination'=>[
                'pageSize'=>50,
            ],
        ]);
    }

}

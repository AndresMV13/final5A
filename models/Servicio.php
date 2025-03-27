<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "servicio".
 *
 * @property int $id
 * @property string $nombre
 *
 * @property Calificacion[] $calificacions
 * @property Tickets[] $tickets
 * @property UsuarioServicio[] $usuarioServicios
 */
class Servicio extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'servicio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre'], 'required'],
            [['nombre'], 'match', 'pattern' => '/^(?![\s\S]*[^a-zA-Z0-9\s])[\w\s]*$/', 'message' => 'El campo no puede contener solo caracteres especiales.'],
            [['nombre'], 'string', 'max' => 100, 'min'=>3],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
        ];
    }

    /**
     * Gets query for [[Calificacions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCalificacions()
    {
        return $this->hasMany(Calificacion::class, ['id_servicio' => 'id']);
    }


    public function getCalificacionsAQ()
    {
        $query= $this->hasMany(Calificacion::class, ['id_servicio' => 'id']);
        return new ActiveDataProvider([
            'query'=>$query,
            'pagination'=>[
                'pageSize'=>10,
            ],
        ]);
    }

    /**
     * Gets query for [[Tickets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTickets()
    {
        return $this->hasMany(Tickets::class, ['id_servicio' => 'id']);
    }

    /**
     * Gets query for [[UsuarioServicios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioServicios()
    {
        return $this->hasMany(UsuarioServicio::class, ['id_servicio' => 'id']);
    }


    public function getUsuariosAQ()
    {
        $query= $this->hasMany(User::class, ['id' => 'id_usuario'])
        ->viaTable('usuario_servicio',['id_servicio'=>'id'])
        ->innerJoin('usuario_servicio','usuario_servicio.id_usuario = usuario.id');
        return new ActiveDataProvider([
            'query'=>$query,
            'pagination'=>[
                'pageSize'=>10,
            ],
        ]);
    }

    public static function promedio($id_servicio){
        $query= (new \yii\db\Query())
            ->select('AVG(calificacion)')
            ->from('calificacion')
            ->where(['id_servicio'=>$id_servicio]);
            
            return $promedio=$query->scalar();

    }    
}

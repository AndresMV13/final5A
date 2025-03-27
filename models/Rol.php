<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\models\Seccion;
/**
 * This is the model class for table "rol".
 *
 * @property int $id
 * @property string $nombre
 *
 * @property Permisos[] $permisos
 * @property Usuario[] $usuarios
 */
class Rol extends \yii\db\ActiveRecord
{

    public $seccionesSeleccionadas;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rol';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre'], 'required'],
            [['nombre'], 'match', 'pattern' => '/^(?![\s\S]*[^a-zA-Z0-9\s])[\w\s]*$/', 'message' => 'El campo no puede contener solo caracteres especiales.'],
            [['nombre'], 'string', 'max' => 20, 'min'=>3],
            [['seccionesSeleccionadas'], 'safe'],
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
     * Gets query for [[Permisos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPermisos()
    {

        $query = $this->hasMany(Permisos::class, ['id_rol' => 'id']);

        return new ActiveDataProvider([
            'query'=>$query,
            'pagination'=>[
                'pageSize'=>10,
            ]
    ]);
    }

    public function getPermisosAQ(){

        return $this->hasMany(Permisos::class,['id_rol'=>'id']);
    }

    public function getPermisosActive(){

        return $this->hasMany(Permisos::class,['id_rol'=>'id'])
        ->where(['estatus'=>'activo']);
    }

    /**
     * Gets query for [[Usuarios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarios()
    {
        $query = $this->hasMany(User::class, ['id_rol' => 'id']);

    return new ActiveDataProvider([
        'query' => $query,
        'pagination' => [
            'pageSize' => 10, 
        ],
    ]);
    }

    public function getSecciones($id_rol)
{
    /*  
        Find se usa para consultas, nos devuelve un objeto tipo AQ, con el podemos encadenar metodos de consulta.

        
    */
    $query = Seccion::find()
        ->innerJoin('permisos', 'permisos.id_seccion = seccion.id')
        ->where(['permisos.id_rol' => $id_rol])
        ->andWhere(['permisos.estatus' => 'activo']); 

    return new ActiveDataProvider([
        'query' => $query,
        'pagination' => [
            'pageSize' => 10,
        ],
    ]);
}

    public function getSeccionesAQ()
    {
        return  $this->hasMany(Seccion::class, ['id'=>'id_seccion'])
            ->viaTable('permisos',['id_rol'=>'id']);
    }



    

}

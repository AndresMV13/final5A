<?php
namespace app\models;

use yii\base\Model;

class FormVerificarSeguridad extends Model
{
    public $respuesta_seguridad;

public function rules() {
    return [
        [['respuesta_seguridad'], 'required'],
        [['respuesta_seguridad'], 'string'],
    ];
}

// En app\models\FormVerificarSeguridad.php
public function scenarios()
{
    return [
        'recuperarCuenta' => ['respuesta_seguridad'],
    ];
}

}

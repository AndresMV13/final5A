<?php
namespace app\models;

use yii\base\Model;

class FormRecuperar extends Model
{
    public $correo;

    public function rules()
    {
        return [
            [['correo'], 'required'],
            [['correo'], 'email'],
        ];
    }
}

<?php
namespace app\models;
use app\models\User;
use Yii;

class PasswordResetRequestForm extends \yii\base\Model
{
    public $correo;
    public $respuesta_seguridad;     
    
public function rules()
{
    return [
        ['correo', 'required'],
        ['correo', 'email'],
        ['correo', 'exist',
            'targetClass' => User::className(),
            'targetAttribute' => 'correo', // <- Esto es clave!
            'message' => 'No existe usuario con este correo'
        ],
    ];
}

     
     public function sendEmail()
     {
         $user = User::findOne(['correo'=>$this->correo]);
         
         if (!$user) {
             return false;
         }
         
         if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
             $user->generatePasswordResetToken();
             if (!$user->save()) {
                 return false;
             }
         }
         
         return Yii::$app->mailer->compose(
             ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
             ['user' => $user]
         )
         ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' Robot'])
         ->setTo($this->correo)
         ->setSubject('Recuperación de contraseña para ' . Yii::$app->name)
         ->send();
     }
 }
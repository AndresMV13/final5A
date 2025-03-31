<?php
namespace app\models;
use Yii;
class ResetPasswordForm extends \yii\base\Model
{
    public $password;
    public $password_repeat;
    
    private $_user;
    
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException('El token de recuperación no puede estar vacío.');
        }
        
        $this->_user = User::findByPasswordResetToken($token);
        if (!$this->_user) {
            throw new InvalidParamException('Token de recuperación inválido.');
        }
        parent::__construct($config);
    }
    
    public function rules()
    {
        return [
            [['password', 'password_repeat'], 'required'],
            ['password', 'string', 'min' => 8],
            ['password_repeat', 'compare', 'compareAttribute' => 'password'],
        ];
    }
    
    public function resetPassword()
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();
        
        return $user->save(false);
    }
}
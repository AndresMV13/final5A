<?php
/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Acceso a Codex Nenis';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    :root {
        --primary-color: #6a0dad;
        --primary-dark: #4b0082;
        --secondary-color: #1a1a2e;
        --accent-color: #ff6b6b;
        --light-color: #f8f9fa;
        --gradient: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    }
    
    .gaming-login {
        max-width: 500px;
        margin: 0 auto;
        padding: 2.5rem;
        background: #1a1a23;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        border-top: 4px solid var(--accent-color);
        color: white;
    }
    
    .login-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .login-header h1 {
        font-weight: 800;
        color: white;
        margin-bottom: 0.5rem;
        font-size: 2.2rem;
    }
    
    .login-header p {
        color: rgba(255,255,255,0.7);
        font-size: 1.1rem;
    }
    
    .form-control {
        background-color: #2a2a3a;
        border: 1px solid #3a3a4a;
        color: white;
        padding: 0.8rem 1rem;
        border-radius: 8px;
    }
    
    .form-control:focus {
        background-color: #2a2a3a;
        color: white;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(106, 13, 173, 0.25);
    }
    
    .btn-login {
        background: var(--gradient);
        border: none;
        padding: 0.8rem;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.3s ease;
        width: 100%;
        margin-top: 1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .btn-login:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(106, 13, 173, 0.4);
    }
    
    .custom-checkbox .custom-control-input:checked~.custom-control-label::before {
        background-color: var(--primary-color);
        border-color: var(--primary-dark);
    }
    
    .login-links {
        margin-top: 1.5rem;
        text-align: center;
    }
    
    .login-links a {
        color: var(--accent-color);
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .login-links a:hover {
        color: white;
        text-decoration: underline;
    }
    
    .brand-logo {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .brand-logo img {
        height: 50px;
    }
    
    body {
        background: url('https://images.unsplash.com/photo-1542751371-adc38448a05e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80') no-repeat center center fixed;
        background-size: cover;
        display: flex;
        align-items: center;
        min-height: 100vh;
    }
    
    .site-login {
        width: 100%;
        padding: 2rem;
    }
    
    .invalid-feedback {
        color: #ff6b6b;
    }
</style>

<div class="site-login">
    <div class="gaming-login">
        <div class="brand-logo">
            <img src="https://via.placeholder.com/150x50/6a0dad/ffffff?text=CODEX+NENIS" alt="Codex Nenis">
        </div>
        
        <div class="login-header">
            <h1>INICIO DE SESIÓN</h1>
            <p>Ingresa tus credenciales para acceder a tu cuenta</p>
        </div>

        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'fieldConfig' => [
                'template' => "<div class=\"mb-3\">{label}\n{input}\n{error}</div>",
                'labelOptions' => ['class' => 'form-label'],
                'inputOptions' => ['class' => 'form-control'],
                'errorOptions' => ['class' => 'invalid-feedback'],
            ],
        ]); ?>

        <?= $form->field($model, 'correo')->textInput([
            'autofocus' => true,
            'placeholder' => 'tu@correo.com'
        ]) ?>

        <?= $form->field($model, 'password')->passwordInput([
            'placeholder' => '••••••••'
        ]) ?>

        <?= $form->field($model, 'rememberMe')->checkbox([
            'template' => "<div class=\"form-check mb-3\">{input} {label}</div>\n<div>{error}</div>",
            'class' => 'form-check-input',
            'labelOptions' => ['class' => 'form-check-label']
        ]) ?>

        <div class="form-group">
            <?= Html::submitButton('ACCEDER', [
                'class' => 'btn btn-login', 
                'name' => 'login-button'
            ]) ?>
        </div>

        <?php ActiveForm::end(); ?>

        <div class="login-links">
            <?= Html::a('¿Olvidaste tu contraseña?', ['site/request-password-reset']) ?>
            <span class="mx-2">|</span>
            <?= Html::a('Registrarme', ['usuario/create-clientes'], ['class' => 'font-weight-bold']) ?>
        </div>
    </div>
</div>
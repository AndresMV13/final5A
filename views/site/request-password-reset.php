<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* VERIFICACIÓN DE SEGURIDAD */
if (!isset($model)) {
    $model = new \app\models\PasswordResetRequestForm(); // Creación de emergencia
}

$this->title = 'Recuperar Contraseña';
?>

<div class="site-request-password-reset">
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
    
        <?= $form->field($model, 'correo')->textInput(['autofocus' => true]) ?>
        
        <div class="form-group">
            <?= Html::submitButton('Enviar', ['class' => 'btn btn-primary']) ?>
        </div>
    
    <?php ActiveForm::end(); ?>
</div>
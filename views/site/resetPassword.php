<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Resetear contraseña';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-reset-password">
    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>Por favor ingresa tu nueva contraseña:</p>
    
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
            
                <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>
                <?= $form->field($model, 'password_repeat')->passwordInput() ?>
                
                <div class="form-group">
                    <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary']) ?>
                </div>
            
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
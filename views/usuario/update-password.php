<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Actualizar Contraseña';
$this->params['breadcrumbs'][] = ['label' => 'Mi Perfil', 'url' => ['my-view']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="usuario-update-password col-md-6 col-md-offset-3">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin([
                'id' => 'password-change-form',
                'enableClientValidation' => true,
            ]); ?>

            <div class="alert alert-warning text-center">
                <i class="glyphicon glyphicon-user"></i> <?= Html::encode(Yii::$app->user->identity->correo) ?>
            </div>

            <?= $form->field($model, 'currentPassword', [
                'inputOptions' => [
                    'placeholder' => 'Contraseña actual',
                    'class' => 'form-control',
                    'autocomplete' => 'current-password'
                ]
            ])->passwordInput()->label('Contraseña Actual') ?>

            <?= $form->field($model, 'newPassword', [
                'inputOptions' => [
                    'placeholder' => 'Mínimo 8 caracteres',
                    'class' => 'form-control',
                    'autocomplete' => 'new-password',
                    'id' => 'newPassword'
                ]
            ])->passwordInput()->label('Nueva Contraseña') ?>

            <?= $form->field($model, 'newPasswordRepeat', [
                'inputOptions' => [
                    'placeholder' => 'Repetir nueva contraseña',
                    'class' => 'form-control',
                    'autocomplete' => 'new-password',
                    'id' => 'newPasswordRepeat'
                ]
            ])->passwordInput()->label('Confirmar Contraseña') ?>

            <div class="form-group text-center">
                <?= Html::submitButton('Actualizar Contraseña', [
                    'class' => 'btn btn-primary btn-lg',
                    'name' => 'change-password-button'
                ]) ?>
                <?= Html::a('Cancelar', ['my-view'], ['class' => 'btn btn-default btn-lg']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php
$this->registerJs(<<<JS
// Validación en cliente para contraseñas iguales
$('#password-change-form').on('beforeSubmit', function(e) {
    if ($('#newPassword').val() !== $('#newPasswordRepeat').val()) {
        alert('Las contraseñas nuevas no coinciden');
        return false;
    }
    return true;
});
JS
);
?>
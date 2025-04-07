<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Restablecer Contraseña';
?>

<h1><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin(); ?>

<?= Html::label('Nueva Contraseña') ?>
<?= Html::passwordInput('password', '', ['class' => 'form-control']) ?>

<?= Html::label('Repetir Contraseña') ?>
<?= Html::passwordInput('password_repeat', '', ['class' => 'form-control']) ?>

<br>
<div class="form-group">
    <?= Html::submitButton('Actualizar Contraseña', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>

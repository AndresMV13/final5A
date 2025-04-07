<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\User $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="usuario-form">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(); ?>
    

    <?= $form->field($model, 'id_rol')->hiddenInput(['value' => 3])->label(false) ?>
    <?= $form->field($model, 'status')->hiddenInput(['value' => 'activo'])->label(false) ?>


    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'apellido_paterno')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'apellido_materno')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'correo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'password_repeat')->passwordInput() ?>


    <?= $form->field($model, 'pregunta_seguridad')->dropDownList([
    '¿Cuál es el nombre de tu primera mascota?' => '¿Cuál es el nombre de tu primera mascota?',
    '¿Cuál es tu bebida favorita' => '¿Cuál es tu bebida favorita?',
    '¿Superheroe favorito?' => '¿Superheroe favorito?',
    '¿Cuál es el segundo nombre de tu madre?' => '¿Cuál es el segundo nombre de tu madre?',
    '¿Cuál es tu comida favorita?' => '¿Cuál es tu comida favorita?',
], ['prompt' => 'Selecciona una pregunta de seguridad']) ?>


    <?= $form->field($model, 'respuesta_seguridad_hash')->textInput(['maxlength'=>true])?>

    <div class="form-group">
        <?= Html::submitButton('Registrarse como Cliente', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

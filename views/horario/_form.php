<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Horario $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="horario-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'dias')->dropDownList([ 'lunes-viernes' => 'Lunes-viernes', 'sabado-domingo' => 'Sabado-domingo', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'turno')->dropDownList([ 'matutino' => 'Matutino', 'vespertino' => 'Vespertino', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'hora_entrada_esperada')->textInput() ?>

    <?= $form->field($model, 'hora_salida_esperada')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

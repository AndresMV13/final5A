<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\CalificacionSoporte $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="calificacion-soporte-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'p1')->textInput() ?>

    <?= $form->field($model, 'p2')->textInput() ?>

    <?= $form->field($model, 'p3')->textInput() ?>

    <?= $form->field($model, 'p4')->textInput() ?>

    <?= $form->field($model, 'p5')->textInput() ?>

    <?= $form->field($model, 'id_operador')->textInput() ?>

    <?= $form->field($model, 'numero_serie')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

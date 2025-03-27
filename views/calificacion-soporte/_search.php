<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\CalificacionSoporteSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="calificacion-soporte-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'p1') ?>

    <?= $form->field($model, 'p2') ?>

    <?= $form->field($model, 'p3') ?>

    <?= $form->field($model, 'p4') ?>

    <?php // echo $form->field($model, 'p5') ?>

    <?php // echo $form->field($model, 'id_operador') ?>

    <?php // echo $form->field($model, 'numero_serie') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

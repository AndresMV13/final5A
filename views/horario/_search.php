<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\HorarioSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="horario-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'dias') ?>

    <?= $form->field($model, 'turno') ?>

    <?= $form->field($model, 'hora_entrada_esperada') ?>

    <?= $form->field($model, 'hora_salida_esperada') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

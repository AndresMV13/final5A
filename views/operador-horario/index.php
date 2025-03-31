<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Asignar Horario a Operador';
?>

<h1><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin(['action' => ['operador-horario/asignar']]); ?>

<?= $form->field($model, 'usuario_id')->dropDownList(
    \yii\helpers\ArrayHelper::map($operadores, 'id', 'correo'),
    ['prompt' => 'Seleccione un operador']
) ?>

<?= $form->field($model, 'horario_id')->dropDownList(
    \yii\helpers\ArrayHelper::map($horarios, 'id','dias', 'turno'),
    ['prompt' => 'Seleccione un horario']
) ?>

<div class="form-group">
    <?= Html::submitButton('Asignar Horario', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>


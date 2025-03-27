<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Seccion;
/** @var yii\web\View $this */
/** @var app\models\Rol $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="rol-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'seccionesSeleccionadas')->checkboxList(
    Seccion::find()->select(['nombre', 'id'])->indexBy('id')->column(),
    [
        'value' => $model->seccionesSeleccionadas, 
        'separator' => '<br>',
        
    ]
) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

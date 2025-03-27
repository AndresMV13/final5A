<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Tickets $model */
/** @var yii\widgets\ActiveForm $form */
/** @var array $servicios */

?>

<div class="tickets-form">

    <?php $form = ActiveForm::begin(); 
        ?>


    <?= $form->field($model, 'id_servicio')->dropdownList(
    \yii\helpers\ArrayHelper::map($servicios, 'id', 'nombre'),
    ['prompt' => 'Seleccione un servicio']
) ?>
    <?= $form->field($model, 'descripcion')->textarea(['rows' => 6, 'columns'=>6]) ?>



    <div class="form-group">
        <?= Html::submitButton('Levantar Ticket', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm; 
use app\models\UsuarioServicio;
/** @var yii\web\View $this */
/** @var app\models\Calificacion $model */

$this->title = 'Calificar mis servicios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="calificacion-create">

    <h1><?= Html::encode($this->title) ?></h1>

    
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'id_usuario')->hiddenInput(['value'=>Yii::$app->user->identity->id])->label(false) ?>
    <?= $form->field($model, 'id_servicio')->dropDownList(
    \yii\helpers\ArrayHelper::map(
        $serviciosContratados, // Usamos la lista de servicios obtenida en el controlador
        'id_servicio', // Clave de los servicios
        function ($servicio) { return $servicio->servicio->nombre; } // Nombre del servicio
    ),
    ['prompt' => 'Selecciona un servicio']
) ?>

    <?= $form->field($model, 'calificacion')->dropDownList([ 1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5', ], ['prompt' => '']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

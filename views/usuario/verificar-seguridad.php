<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\FormVerificarSeguridad $model */
/** @var string $pregunta */

$this->title = 'Verificación de seguridad';
?>

<h1><?= Html::encode($this->title) ?></h1>

<p><strong>Pregunta de seguridad:</strong> <?php echo $user->pregunta_seguridad ?></p>

<div class="verificar-seguridad-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'respuesta_seguridad')->passwordInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Verificar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

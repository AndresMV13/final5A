<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Recuperar Contraseña';
?>

<h1><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'correo')->textInput(['maxlength' => true]) ?>

<?php
// Mostrar la pregunta si ya se envió el correo y se encontró
if (Yii::$app->request->isPost && $model->correo) {
    $usuario = \app\models\User::find()->where(['correo' => $model->correo])->one();
    if ($usuario) {
        echo "<p><strong>Pregunta de seguridad:</strong> {$usuario->pregunta_seguridad}</p>";
        echo $form->field($model, 'respuesta_seguridad')->passwordInput();
    }
}
?>

<div class="form-group">
    <?= Html::submitButton('Verificar', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>

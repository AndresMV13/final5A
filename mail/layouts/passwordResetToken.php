<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>

<p>Hola <?= Html::encode($user->username) ?>,</p>

<p>Sigue el siguiente enlace para resetear tu contraseña:</p>

<p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>

<p>Si no solicitaste este cambio, por favor ignora este correo.</p>
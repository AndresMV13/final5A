<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\UsuarioServicio $model */

$this->title = 'Create Usuario Servicio';
$this->params['breadcrumbs'][] = ['label' => 'Usuario Servicios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usuario-servicio-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

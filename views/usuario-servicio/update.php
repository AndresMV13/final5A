<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\UsuarioServicio $model */

$this->title = 'Update Usuario Servicio: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Usuario Servicios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="usuario-servicio-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

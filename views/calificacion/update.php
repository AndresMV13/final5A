<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Calificacion $model */

$this->title = 'Update Calificacion: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Calificacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="calificacion-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

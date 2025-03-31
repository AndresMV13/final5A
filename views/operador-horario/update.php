<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\OperadorHorario $model */

$this->title = 'Update Operador Horario: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Operador Horarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="operador-horario-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

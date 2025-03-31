<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\OperadorHorario $model */

$this->title = 'Create Operador Horario';
$this->params['breadcrumbs'][] = ['label' => 'Operador Horarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operador-horario-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

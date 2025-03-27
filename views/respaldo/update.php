<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Respaldo $model */

$this->title = 'Update Respaldo: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Respaldos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="respaldo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

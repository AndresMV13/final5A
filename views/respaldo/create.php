<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Respaldo $model */

$this->title = 'Create Respaldo';
$this->params['breadcrumbs'][] = ['label' => 'Respaldos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="respaldo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

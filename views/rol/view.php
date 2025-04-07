<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\data\ActiveDataProvider;
use yii\models\Rol;

/** @var yii\web\View $this */
/** @var app\models\Rol $model */

$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Roles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$dataProvider=$model->getUsuarios();
$dataProvider2=$model->getSecciones($model->id);
?>
<div class="rol-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
        
        ],
    ]) ?>
    <?= $this->render('_users',['dataProvider'=>$dataProvider]) ?>
    <?= $this->render('_permissions',['dataProvider'=>$dataProvider2]) ?>

</div>

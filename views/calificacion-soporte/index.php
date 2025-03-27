<?php

use app\models\CalificacionSoporte;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\CalificacionSoporteSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Calificacion Soportes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="calificacion-soporte-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Calificacion Soporte', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'p1',
            'p2',
            'p3',
            'p4',
            //'p5',
            //'id_operador',
            //'numero_serie',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, CalificacionSoporte $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>

<?php

use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\UsuarioSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Staff';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usuario-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Registrar Personal', ['create-staff'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'nombre',
            'apellido_paterno',
            'apellido_materno',
            'correo',
            'id_rol',
            
            //'password',
            //'salt',
            //'status',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, User $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 },'buttons' => [
        'delete' => function ($url, $model) {
            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                'title' => 'Eliminar usuario',
                'data' => [
                    'confirm' => '¿Seguro que quieres inhabilitar al miembro del staff?', // Mensaje personalizado
                    'method' => 'post',
                ],
                'class' => 'btn btn-danger btn-sm', // Opcional: agregar estilo al botón
            ]);
        },
    ],
            ],
        ],
    ]); ?>


</div>

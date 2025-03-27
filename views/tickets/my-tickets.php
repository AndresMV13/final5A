<?php

use app\models\Tickets;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\TicketsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Mis Tickets';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tickets-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php  echo (Yii::$app->user->identity->rol->nombre) ?>
    <p>
        <?=Yii::$app->user->identity->rol->nombre=='Cliente'
        ?Html::a('Nuevo Ticket', ['levantar-ticket'], ['class' => 'btn btn-success']): ''
        ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'n_serie',
            [
                'attribute'=>'Servicio',
                'value'=>function($model){
                    return $model->getNombreServicio();
                }
            ],
            Yii::$app->user->identity->id_rol==3
            ?[
                'attribute'=>'Operador',
                'value'=>function($model){
                    return $model->getNombreOperador();
                }
            ]:[
                'attribute'=>'Cliente',
                'value'=>function($model){
                    return $model->getNombreCliente();
                }
            ],
            
            'descripcion:ntext',
            'fecha_creacion',
            'estado',
            [
                'class' => 'yii\grid\ActionColumn', // Puedes mantener esta clase o usar una personalizada
                'template' => '{chat}', // Solo mostramos el botón de chat
                'buttons' => [
                    'chat' => function ($url, $model, $key) {
                        // Mostrar el botón solo si el estado es "abierto"
                        if ($model->estado === 'abierto') {
                            return Html::a(
                                'Ir al Chat', // Texto del botón
                                ['chat/index', 'id' => $model->id], // URL con parámetros
                                ['class' => 'btn btn-primary'] // Clases CSS del botón
                            );
                        }
                        return ''; // Si el estado no es "abierto", no mostrar nada
                    }
                ],
            ],
        ],
    ]); ?>


</div>

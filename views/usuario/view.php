<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = 'Informacion del Trabajador';
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="usuario-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Inhabilitar Trabajador', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estás seguro de que deseas inhabilitar este trabajador?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'rol.nombre',
                'label' => 'Rol',
            ],
            'nombre',
            'apellido_paterno',
            'apellido_materno',
            'correo',
            'status',
        ],
    ]) ?>
     <h2>Asistencias</h2>
    <?php
    // Configurar el data provider para las asistencias
    $dataProvider = new ActiveDataProvider([
        'query' => \app\models\Asistencia::find()
            ->where(['usuario_id' => $model->id])
            ->orderBy(['entrada' => SORT_DESC]),
        'pagination' => [
            'pageSize' => 10,
        ],
    ]);
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [  // Campo concatenado de nombre completo desde la vista
            'entrada:datetime',
            'hora_entrada_esperada',
            'salida:datetime',
            
            'hora_salida_esperada',
            [
                'attribute' => 'estatus_entrada',
                'label' => 'Estatus Entrada',
                'value' => function($model) {
                    return $model->estatus_entrada;
                }
            ],
            [
                'attribute' => 'estatus_salida',
                'label' => 'Estatus Salida',
                'value' => function($model) {
                    return $model->estatus_salida;
                }
            ],
        ],
    ]); ?>

<h2>Calificaciones del Operador</h2>

<?= GridView::widget([
    'dataProvider' => $dataProviderCalificaciones,
    'columns' => [
        'numero_serie',  // Número de serie del ticket
        [
            'attribute' => 'promedio',
            'label' => 'Promedio de Calificación',
            'value' => function($model) {
                return $model->promedio;  // Ahora se calcula dinámicamente en el modelo
            }
        ],
    ],
]); ?>
    <p><strong>Calificación Promedio:</strong> 8.5</p> <!-- Esto puede venir de otro campo o cálculo -->

</div>

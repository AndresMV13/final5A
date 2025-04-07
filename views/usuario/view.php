<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = 'Información del Trabajador';
\yii\web\YiiAsset::register($this);

// Configurar data provider para asistencias
$dataProviderAsistencias = new ActiveDataProvider([
    'query' => \app\models\Asistencia::find()
        ->where(['usuario_id' => $model->id])
        ->orderBy(['entrada' => SORT_DESC]),
    'pagination' => [
        'pageSize' => 10,
    ],
]);

// Configurar data provider para calificaciones según la nueva estructura
$dataProviderCalificaciones = new ActiveDataProvider([
    'query' => \app\models\CalificacionSoporte::find()
        ->where(['id_operador' => $model->id])
        ->orderBy(['id' => SORT_DESC]),
    'pagination' => [
        'pageSize' => 10,
    ],
]);

// Función para calcular el promedio de calificación
function calcularPromedioCalificacion($calificacion) {
    $suma = $calificacion->p1 + $calificacion->p2 + $calificacion->p3 + $calificacion->p4 + $calificacion->p5;
    return $suma / 5;
}
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
        <button id="generar-pdf" class="btn btn-primary">
            <i class="fa fa-file-pdf"></i> Generar Reporte PDF
        </button>
    </p>

    <div id="contenido-reporte">
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
                [
                    'label' => 'Horario Actual Asignado',
                    'value' => function($model) {
                        return $model->horarioActivo ? 
                            $model->horarioActivo->horario->dias . ' (' . 
                            $model->horarioActivo->horario->hora_entrada_esperada . ' - ' . 
                            $model->horarioActivo->horario->hora_salida_esperada . ')' : 
                            'No tiene horario asignado';
                    },
                ],
                'status',
            ],
        ]) ?>

        <h2>Asistencias</h2>
        <?= GridView::widget([
            'dataProvider' => $dataProviderAsistencias,
            'id' => 'grid-asistencias',
            'tableOptions' => ['class' => 'table table-striped table-bordered'],
            'columns' => [
                [
                    'attribute' => 'entrada',
                    'format' => 'datetime',
                    'contentOptions' => ['style' => 'width: 180px;'],
                    
                ],
                [
                    'attribute' => 'hora_entrada_esperada',
                    'contentOptions' => ['style' => 'width: 120px;']
                ],
                [
                    'attribute' => 'salida',
                    'format' => 'datetime',
                    'contentOptions' => ['style' => 'width: 180px;']
                ],
                [
                    'attribute' => 'hora_salida_esperada',
                    'contentOptions' => ['style' => 'width: 120px;']
                ],
                [
                    'attribute' => 'estatus_entrada',
                    'label' => 'Estatus Entrada',
                    'contentOptions' => function($model) {
                        return [
                            'style' => 'color: ' . ($model->estatus_entrada == 'Puntual' ? 'green' : 'red'),
                            'font-weight' => 'bold'
                        ];
                    }
                ],
                [
                    'attribute' => 'estatus_salida',
                    'label' => 'Estatus Salida',
                    'contentOptions' => function($model) {
                        return [
                            'style' => 'color: ' . ($model->estatus_salida == 'Puntual' ? 'green' : 'red'),
                            'font-weight' => 'bold'
                        ];
                    }
                ],
            ],
        ]); ?>

        <h2>Calificaciones del Operador</h2>
        <?= GridView::widget([
            'dataProvider' => $dataProviderCalificaciones,
            'id' => 'grid-calificaciones',
            'tableOptions' => ['class' => 'table table-striped table-bordered'],
            'columns' => [
                [
                    'attribute' => 'numero_serie',
                    'label' => 'Número de Serie',
                    'contentOptions' => ['style' => 'width: 150px;']
                ],
                [
                    'label' => 'Pregunta 1',
                    'value' => function($model) {
                        return $model->p1 . '/5';
                    }
                ],
                [
                    'label' => 'Pregunta 2',
                    'value' => function($model) {
                        return $model->p2 . '/5';
                    }
                ],
                [
                    'label' => 'Pregunta 3',
                    'value' => function($model) {
                        return $model->p3 . '/5';
                    }
                ],
                [
                    'label' => 'Pregunta 4',
                    'value' => function($model) {
                        return $model->p4 . '/5';
                    }
                ],
                [
                    'label' => 'Pregunta 5',
                    'value' => function($model) {
                        return $model->p5 . '/5';
                    }
                ],
                [
                    'label' => 'Promedio',
                    'value' => function($model) {
                        $promedio = calcularPromedioCalificacion($model);
                        return number_format($promedio, 1) . '/5';
                    },
                    'contentOptions' => function($model) {
                        $promedio = calcularPromedioCalificacion($model);
                        $color = 'black';
                        if ($promedio >= 4) $color = 'green';
                        elseif ($promedio >= 2.5) $color = 'orange';
                        else $color = 'red';
                        
                        return ['style' => "color: $color; font-weight: bold"];
                    }
                ],
            ],
        ]); ?>
    </div>
</div>

<!-- Incluir las librerías necesarias -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const { jsPDF } = window.jspdf;
    
    document.getElementById("generar-pdf").addEventListener("click", function() {
        // Crear nuevo PDF en orientación horizontal para mejor visualización de tablas
        const doc = new jsPDF('l', 'pt', 'a4');
        
        // Ocultar botones antes de capturar
        const buttons = document.querySelectorAll('button, a.btn');
        buttons.forEach(btn => btn.style.visibility = 'hidden');
        
        // Capturar contenido como imagen
        html2canvas(document.getElementById("contenido-reporte"), {
            scale: 2,
            logging: true,
            useCORS: true,
            windowHeight: document.getElementById("contenido-reporte").scrollHeight
        }).then(canvas => {
            const imgData = canvas.toDataURL('image/png');
            const imgWidth = doc.internal.pageSize.getWidth() - 40;
            const imgHeight = (canvas.height * imgWidth) / canvas.width;
            
            // Agregar imagen al PDF
            doc.addImage(imgData, 'PNG', 20, 20, imgWidth, imgHeight);
            
            // Guardar el PDF
            doc.save('reporte_trabajador_<?= $model->id ?>_<?= date('YmdHis') ?>.pdf');
            
            // Restaurar visibilidad de botones
            buttons.forEach(btn => btn.style.visibility = 'visible');
        });
    });
});
</script>

<style>
/* Estilos para mejorar la visualización del reporte */
#contenido-reporte {
    background-color: white;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

.table {
    width: 100%;
    margin-bottom: 20px;
}

.table th {
    background-color: #f8f9fa;
    text-align: center;
}

.table td {
    text-align: center;
}

.detail-view th {
    width: 30%;
}

.btn {
    margin-right: 10px;
}

/* Estilos para las tablas en el PDF */
@media print {
    body {
        background: white !important;
    }
    .table {
        page-break-inside: avoid;
    }
    .no-print {
        display: none !important;
    }
}
</style>
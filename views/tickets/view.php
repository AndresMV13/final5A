<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Tickets $model */

$this->title = $model->n_serie;
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$promedio = isset($promedio) ? $promedio : 0;

?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<style>
    /* Estilos para la vista normal */
    .tickets-view {
        font-family: 'Arial', sans-serif;
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        background: #fff;
    }
    
    /* Estilos específicos para el PDF */
    @media print {
        body {
            background: #fff !important;
        }
        .tickets-view {
            padding: 0;
            max-width: 100%;
        }
        .no-print {
            display: none !important;
        }
    }
    
    .pdf-header {
        border-bottom: 2px solid #333;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }
    
    .pdf-title {
        color: #2c3e50;
        text-align: center;
        margin-bottom: 10px;
    }
    
    .pdf-section {
        margin-bottom: 15px;
        padding: 10px;
        background: #f9f9f9;
        border-radius: 5px;
    }
    
    .pdf-section-title {
        font-weight: bold;
        color: #3498db;
        margin-bottom: 5px;
    }
    
    .pdf-footer {
        margin-top: 30px;
        padding-top: 10px;
        border-top: 1px solid #ddd;
        text-align: right;
        font-size: 0.8em;
        color: #7f8c8d;
    }
</style>

<div class="tickets-view" id="contenido-pdf">
    <div class="pdf-header">
        <h1 class="pdf-title">Ticket #<?= Html::encode($this->title) ?></h1>
        <div style="text-align: center; font-style: italic;">
            Fecha de generación: <?= date('d/m/Y H:i') ?>
        </div>
    </div>

    <div class="pdf-section">
        <div class="pdf-section-title">Información del Cliente</div>
        <?= DetailView::widget([
            'model' => $model,
            'options' => ['class' => 'table table-striped table-bordered detail-view'],
            'attributes' => [
                [
                    'attribute'=>'cliente.correo',
                    'label'=>'Correo electrónico',
                    'contentOptions' => ['style' => 'width: 70%']
                ],
            ],
        ]) ?>
    </div>

    <div class="pdf-section">
        <div class="pdf-section-title">Información del Servicio</div>
        <?= DetailView::widget([
            'model' => $model,
            'options' => ['class' => 'table table-striped table-bordered detail-view'],
            'attributes' => [
                [
                    'attribute'=>'servicio.nombre',
                    'label'=>'Servicio contratado'
                ],
                'descripcion:ntext',
                'fecha_creacion',
                'estado',
            ],
        ]) ?>
    </div>

    <div class="pdf-section">
        <div class="pdf-section-title">Información del Operador</div>
        <?= DetailView::widget([
            'model' => $model,
            'options' => ['class' => 'table table-striped table-bordered detail-view'],
            'attributes' => [
                [
                    'attribute'=>'operador.correo',
                    'label'=>'Correo del operador'
                ],
            ],
        ]) ?>
    </div>

    <?php 
    function generarEstrellas($promedio) {
        $estrellas = '';
        $llenas = floor($promedio);
        $vacías = 5 - ceil($promedio);

        for ($i = 0; $i < $llenas; $i++) {
            $estrellas .= '<i class="fa fa-star" style="color: #ffd700;"></i>';
        }

        for ($i = 0; $i < $vacías; $i++) {
            $estrellas .= '<i class="fa fa-star-o" style="color:rgb(10, 10, 10);"></i>';
        }

        return $estrellas;
    }

    $estrellasHtml = generarEstrellas($promedio); ?>
    
    <?php if ($promedio > 0): ?>
    <div class="pdf-section">
        <div class="pdf-section-title">Calificación del Servicio</div>
        <div style="font-size: 24px; text-align: center;">
            <?= $estrellasHtml ?>
            <div style="margin-top: 5px; font-size: 14px;">
                Puntuación: <?= number_format($promedio, 1) ?>/5
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="pdf-section">
        <div class="pdf-section-title">Calificación del Servicio</div>
        <div style="text-align: center; font-style: italic;">
            No se ha registrado calificación para este ticket.
        </div>
    </div>
    <?php endif; ?>

    <div class="pdf-footer">
        CodexxNenis - <?= date('Y') ?>
    </div>
    <?php if (Yii::$app->user->identity->id_rol=='1'):?>
    <div class="text-right mt-3 no-print">
        <button class="btn btn-success" id="imprimir-pdf">
            <i class="fas fa-file-pdf"></i> Generar PDF
        </button>
    </div>
    <?php endif; ?>
</div>

<script>
document.getElementById('imprimir-pdf').addEventListener('click', function() {
    const { jsPDF } = window.jspdf;
    
    // Ocultar el botón antes de capturar
    document.getElementById('imprimir-pdf').style.visibility = 'hidden';
    
    html2canvas(document.getElementById('contenido-pdf'), {
        scale: 2, // Mayor resolución
        logging: true,
        useCORS: true,
        allowTaint: true,
        windowHeight: document.getElementById('contenido-pdf').scrollHeight
    }).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        const pdf = new jsPDF('p', 'mm', 'a4');
        const imgProps = pdf.getImageProperties(imgData);
        const pdfWidth = pdf.internal.pageSize.getWidth() - 20; // Márgenes laterales
        const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;
        
        // Calcular posición vertical para centrar
        const position = 10; // Margen superior
        
        pdf.addImage(imgData, 'PNG', 10, position, pdfWidth, pdfHeight);
        pdf.save('ticket-<?= $model->n_serie ?>.pdf');
        
        // Restaurar visibilidad del botón
        document.getElementById('imprimir-pdf').style.visibility = 'visible';
    });
});
</script>
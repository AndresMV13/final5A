<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var app\models\Tickets $model */
?>

<div class="card shadow-sm mb-4 ticket-card" style="border-radius: 10px; border-left: 4px solid <?= $model->getStatusColor() ?>;">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
            <h5 class="card-title text-primary mb-3">
                <i class="fas fa-ticket-alt me-2"></i><?= Html::encode($model->n_serie) ?>
            </h5>
            <span class="badge rounded-pill <?= $model->getStatusBadgeClass() ?>">
                <?= Html::encode($model->estado) ?>
            </span>
        </div>
        
        <p class="card-text text-muted mb-3">
            <i class="fas fa-align-left me-2"></i><?= Html::encode($model->descripcion) ?>
        </p>
        
        <div class="ticket-meta mb-3">
            <div class="meta-item">
                <i class="fas fa-concierge-bell text-secondary me-2"></i>
                <span class="fw-bold">Servicio:</span>
                <?= Html::encode($model->servicio->nombre) ?>
            </div>
            
            <div class="meta-item">
                <i class="fas fa-user-headset text-secondary me-2"></i>
                <span class="fw-bold">Operador:</span>
                <?= Html::encode($model->operador->nombreCompleto ?? $model->operador->correo) ?>
            </div>
            
            <?php if ($model->fecha_creacion): ?>
            <div class="meta-item">
                <i class="far fa-calendar-alt text-secondary me-2"></i>
                <span class="fw-bold">Creado:</span>
                <?= Yii::$app->formatter->asDatetime($model->fecha_creacion) ?>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="d-flex justify-content-between align-items-center">
            <a href="<?= Url::to(['tickets/view', 'id' => $model->id]) ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-eye me-1"></i> Ver detalles
            </a>
            
            
        </div>
    </div>
</div>

<style>
    .ticket-card {
        transition: all 0.3s ease;
        border: 1px solid #eaeaea;
    }
    
    .ticket-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .ticket-meta {
        background-color: #f9f9f9;
        padding: 12px;
        border-radius: 8px;
    }
    
    .meta-item {
        margin-bottom: 8px;
    }
    
    .meta-item:last-child {
        margin-bottom: 0;
    }
    
    /* Colores para estados (debes implementar getStatusColor() y getStatusBadgeClass() en tu modelo) */
    .badge-estado-pendiente {
        background-color: #ffc107;
        color: #212529;
    }
    
    .badge-estado-en-proceso {
        background-color: #17a2b8;
        color: white;
    }
    
    .badge-estado-completado {
        background-color: #28a745;
        color: white;
    }
    
    .badge-estado-cancelado {
        background-color: #dc3545;
        color: white;
    }
</style>
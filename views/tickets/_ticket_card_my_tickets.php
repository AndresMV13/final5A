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
                <?= Html::encode($model->getNombreServicio()) ?>
            </div>
            <?php if(Yii::$app->user->identity->id_rol===2):?>
            <div class="meta-item">
                <i class="fas fa-user text-secondary me-2"></i>
                <span class="fw-bold">Cliente:</span>
                <?= Html::encode($model->cliente->correo) ?>
            </div>
            <?php endif;?>

            <?php if(Yii::$app->user->identity->id_rol===3):?>
            <div class="meta-item">
                <i class="fas fa-user-headset text-secondary me-2"></i>
                <span class="fw-bold">Operador:</span>
                <?= Html::encode($model->operador->correo) ?>
            </div>
            <?php endif;?>
            
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
            
            
                <a href="<?= Url::to(['chat/index', 'id' => $model->id]) ?>" class="btn btn-success btn-sm">
                    <i class="fas fa-comments me-1"></i> Ir al Chat
                </a>
            
        </div>
    </div>
</div>

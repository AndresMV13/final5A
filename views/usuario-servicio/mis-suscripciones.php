<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\data\ActiveDataProvider;
use app\models\UsuarioServicio;
?>
<div class="subscription-list row">
    <?php foreach ($suscripciones as $suscripcion): ?>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card subscription-card h-100 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="h5 mb-0 text-primary"><?= Html::encode($suscripcion->servicio->nombre) ?></h3>
                    <span class="badge <?= $suscripcion->estatus == 'activo' ? 'bg-success' : 'bg-secondary' ?>">
                        <?= ucfirst($suscripcion->estatus) ?>
                    </span>
                </div>
                <div class="card-body">
                    <div class="subscription-details">
                        <div class="detail-item">
                            <span class="detail-label">Fecha inicio:</span>
                            <span class="detail-value"><?= Yii::$app->formatter->asDate($suscripcion->fecha_contratacion) ?></span>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <?php if ($suscripcion->estatus == 'activo'): ?>
                        <?= Html::a(
                            'Solicitar cancelación', 
                            ['usuario-servicio/cancelar', 'id' => $suscripcion->id], 
                            [
                                'class' => 'btn btn-outline-danger btn-sm',
                                'data' => [
                                    'confirm' => '¿Estás seguro que deseas cancelar esta suscripción?',
                                    'method' => 'post',
                                ]
                            ]
                        ) ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<style>
    .subscription-card {
        border-radius: 10px;
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.1);
    }
    
    .subscription-card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    
    .subscription-details {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .detail-item {
        display: flex;
        justify-content: space-between;
    }
    
    .detail-label {
        font-weight: 500;
        color: #6c757d;
    }
    
    .detail-value {
        color: #495057;
    }
    
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
</style>
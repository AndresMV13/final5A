<?php
use app\models\UsuarioServicio;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
?>
<div class="pending-requests-container">
    <h2 class="mb-4 text-center">Solicitudes de Cancelación Pendientes</h2>
    
    <div class="row">
        <?php foreach ($pendientes as $pendiente): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card pending-card shadow-sm border-0">
                    <div class="card-header bg-warning text-white">
                        <h3 class="h5 mb-0"><?= Html::encode($pendiente->servicio->nombre) ?></h3>
                    </div>
                    <div class="card-body">
                        <div class="request-info mb-3">
                            <div class="info-item">
                                <i class="fas fa-user me-2"></i>
                                <span><?= Html::encode($pendiente->usuario->correo ?? 'Usuario') ?></span>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between action-buttons">
                            <?= Html::a(
                                '<i class="fas fa-check me-2"></i>Aceptar', 
                                ['usuario-servicio/aceptar-cancelacion', 'id' => $pendiente->id], 
                                [
                                    'class' => 'btn btn-success btn-sm flex-grow-1 me-2',
                                    'data' => [
                                        'confirm' => '¿Confirmas que deseas aceptar esta cancelación?',
                                        'method' => 'post',
                                    ]
                                ]
                            ) ?>
                            
                            <?= Html::a(
                                '<i class="fas fa-times me-2"></i>Rechazar', 
                                ['usuario-servicio/rechazar-cancelacion', 'id' => $pendiente->id], 
                                [
                                    'class' => 'btn btn-danger btn-sm flex-grow-1',
                                    'data' => [
                                        'confirm' => '¿Confirmas que deseas rechazar esta solicitud de cancelación?',
                                        'method' => 'post',
                                    ]
                                ]
                            ) ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
    .pending-card {
        border-radius: 10px;
        overflow: hidden;
        transition: transform 0.3s ease;
    }
    
    .pending-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .request-info {
        background-color: #f8f9fa;
        padding: 12px;
        border-radius: 8px;
    }
    
    .info-item {
        margin-bottom: 8px;
        display: flex;
        align-items: center;
    }
    
    .info-item:last-child {
        margin-bottom: 0;
    }
    
    .action-buttons {
        gap: 10px;
    }
    
    .card-header {
        font-weight: 600;
    }
    
    @media (max-width: 768px) {
        .action-buttons {
            flex-direction: column;
        }
        
        .action-buttons .btn {
            width: 100%;
            margin-bottom: 8px;
            margin-right: 0 !important;
        }
    }
</style>
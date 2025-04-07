<?php
use app\models\Servicio;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
?>

<div class="servicios-list row">
    <?php foreach ($servicios as $servicio): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm servicio-card">
                <div class="card-header bg-primary text-white">
                    <h3 class="h5 mb-0"><?= Html::encode($servicio->nombre) ?></h3>
                </div>
                <div class="card-body d-flex flex-column">
                    <p class="card-text flex-grow-1"><?= Html::encode($servicio->descripcion) ?></p>
                    <div class="mt-auto">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-success precio-tag">
                                $<?= number_format($servicio->precio, 2) ?>
                            </span>
                            <?= Html::a(
                                'Suscribirse', 
                                ['usuario-servicio/suscribir', 'id_servicio' => $servicio->id], 
                                ['class' => 'btn btn-outline-primary btn-sm']
                            ) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<style>
    .servicio-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .servicio-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .precio-tag {
        font-size: 1.1rem;
        padding: 5px 10px;
        border-radius: 5px;
    }
    
    .card-header {
        border-bottom: none;
    }
</style>
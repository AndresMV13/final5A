<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\YiiAsset;

/** @var yii\web\View $this */
/** @var app\models\Usuario $operador */

$this->title = 'Dashboard del Operador';
YiiAsset::register($this);

// Consultas optimizadas según tu estructura de base de datos
$operadorId = Yii::$app->user->id;

// Estadísticas básicas
$stats = Yii::$app->db->createCommand("
    SELECT 
        COUNT(*) AS total,
        SUM(CASE WHEN estado = 'abierto' THEN 1 ELSE 0 END) AS abiertos,
        SUM(CASE WHEN estado = 'en progreso' THEN 1 ELSE 0 END) AS en_progreso,
        SUM(CASE WHEN estado = 'cerrado' THEN 1 ELSE 0 END) AS cerrados,
        SUM(CASE WHEN fecha_creacion >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 1 ELSE 0 END) AS recientes
    FROM tickets 
    WHERE id_operador = :operadorId
", [':operadorId' => $operadorId])->queryOne();

// Distribución por servicio
$servicios = Yii::$app->db->createCommand("
    SELECT s.nombre, COUNT(t.id) as cantidad
    FROM tickets t
    JOIN servicio s ON t.id_servicio = s.id
    WHERE t.id_operador = :operadorId
    GROUP BY s.nombre
    ORDER BY cantidad DESC
    LIMIT 5
", [':operadorId' => $operadorId])->queryAll();

// Tiempo promedio de resolución (en días)
$tiempoPromedio = Yii::$app->db->createCommand("
    SELECT AVG(DATEDIFF(NOW(), fecha_creacion)) as promedio
    FROM tickets
    WHERE id_operador = :operadorId AND estado = 'cerrado'
", [':operadorId' => $operadorId])->queryScalar();

// Tickets recientes con información de servicio
$ticketsRecientes = Yii::$app->db->createCommand("
    SELECT t.*, s.nombre as servicio_nombre, u.nombre as cliente_nombre
    FROM tickets t
    JOIN servicio s ON t.id_servicio = s.id
    JOIN usuario u ON t.id_cliente = u.id
    WHERE t.id_operador = :operadorId
    ORDER BY t.fecha_creacion DESC
    LIMIT 5
", [':operadorId' => $operadorId])->queryAll();
?>

<div class="operador-dashboard">
    <h1><?= Html::encode($this->title) ?></h1>
    
    <div class="row">
        <!-- Tarjeta 1: Tickets Totales -->
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Total Tickets</div>
                <div class="card-body">
                    <h1 class="card-title text-center"><?= $stats['total'] ?></h1>
                    <p class="card-text text-center">Asignados a usted</p>
                </div>
            </div>
        </div>
        
        <!-- Tarjeta 2: Tickets Cerrados -->
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Tickets Cerrados</div>
                <div class="card-body">
                    <h1 class="card-title text-center"><?= $stats['cerrados'] ?></h1>
                    <p class="card-text text-center">Resueltos satisfactoriamente</p>
                </div>
            </div>
        </div>
        
        <!-- Tarjeta 3: Tickets Abiertos -->
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Tickets Abiertos</div>
                <div class="card-body">
                    <h1 class="card-title text-center"><?= $stats['abiertos'] ?></h1>
                    <p class="card-text text-center">Pendientes por resolver</p>
                </div>
            </div>
        </div>
        
        <!-- Tarjeta 4: Tickets en Progreso -->
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-header">En Progreso</div>
                <div class="card-body">
                    <h1 class="card-title text-center"><?= $stats['en_progreso'] ?></h1>
                    <p class="card-text text-center">En proceso de solución</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <!-- Gráfico de Distribución por Servicio -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Distribución por Tipo de Servicio</h5>
                </div>
                <div class="card-body">
                    <canvas id="servicioChart" height="250"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Gráfico de Estados -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Distribución por Estado</h5>
                    <?php if ($stats['cerrados'] > 0): ?>
                        <small class="text-muted">Tiempo promedio de resolución: <?= number_format($tiempoPromedio, 1) ?> días</small>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <canvas id="estadoChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Últimos Tickets -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Últimos Tickets Asignados</h5>
                    <a href="<?= Url::to(['tickets/my-tickets']) ?>" class="btn btn-sm btn-outline-primary">
                        Ver todos los tickets
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>N° Serie</th>
                                    <th>Cliente</th>
                                    <th>Servicio</th>
                                    <th>Descripción</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ticketsRecientes as $ticket): ?>
                                <tr>
                                    <td><?= Html::encode($ticket['n_serie']) ?></td>
                                    <td><?= Html::encode($ticket['cliente_nombre']) ?></td>
                                    <td><?= Html::encode($ticket['servicio_nombre']) ?></td>
                                    <td><?= substr(Html::encode($ticket['descripcion']), 0, 50) ?>...</td>
                                    <td><?= Yii::$app->formatter->asDate($ticket['fecha_creacion']) ?></td>
                                    <td>
                                        <span class="badge 
                                            <?= $ticket['estado'] == 'cerrado' ? 'bg-success' : '' ?>
                                            <?= $ticket['estado'] == 'abierto' ? 'bg-warning' : '' ?>
                                            <?= $ticket['estado'] == 'en progreso' ? 'bg-info' : '' ?>
                                        ">
                                            <?= ucfirst($ticket['estado']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= Url::to(['tickets/view', 'id' => $ticket['id']]) ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Ver
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Incluir Chart.js para los gráficos -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Gráfico de servicios
const servicioCtx = document.getElementById('servicioChart').getContext('2d');
new Chart(servicioCtx, {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($servicios, 'nombre')) ?>,
        datasets: [{
            label: 'Tickets por Servicio',
            data: <?= json_encode(array_column($servicios, 'cantidad')) ?>,
            backgroundColor: '#36A2EB',
            borderColor: '#1E88E5',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Gráfico de estados
const estadoCtx = document.getElementById('estadoChart').getContext('2d');
new Chart(estadoCtx, {
    type: 'pie',
    data: {
        labels: ['Abiertos', 'En Progreso', 'Cerrados'],
        datasets: [{
            data: [
                <?= $stats['abiertos'] ?>,
                <?= $stats['en_progreso'] ?>,
                <?= $stats['cerrados'] ?>
            ],
            backgroundColor: [
                '#FFCE56',
                '#36A2EB',
                '#4BC0C0'
            ]
        }]
    },
    options: {
        responsive: true
    }
});
</script>

<style>
.operador-dashboard {
    padding: 20px;
}

.card {
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    margin-bottom: 20px;
}

.card:hover {
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
}

.card-header {
    font-weight: bold;
    border-bottom: 1px solid rgba(0,0,0,0.1);
    padding: 15px 20px;
    background: rgba(0,0,0,0.03);
}

.card-body {
    padding: 20px;
}

.table-responsive {
    overflow-x: auto;
}

.badge {
    font-size: 0.85em;
    padding: 5px 10px;
    border-radius: 10px;
}

.text-center {
    text-align: center;
}

.text-muted {
    color: #6c757d;
}

.mt-4 {
    margin-top: 1.5rem;
}

.mb-3 {
    margin-bottom: 1rem;
}
</style>
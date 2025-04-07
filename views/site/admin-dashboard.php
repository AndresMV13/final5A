<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\YiiAsset;

/** @var yii\web\View $this */
/** @var app\models\Usuario $admin */

$this->title = 'Dashboard Administrativo';
YiiAsset::register($this);

// Consultas para estadísticas globales
$stats = Yii::$app->db->createCommand("
    SELECT 
        COUNT(*) AS total_tickets,
        SUM(CASE WHEN estado = 'abierto' THEN 1 ELSE 0 END) AS abiertos,
        SUM(CASE WHEN estado = 'en progreso' THEN 1 ELSE 0 END) AS en_progreso,
        SUM(CASE WHEN estado = 'cerrado' THEN 1 ELSE 0 END) AS cerrados,
        COUNT(DISTINCT id_cliente) AS clientes_activos,
        COUNT(DISTINCT id_operador) AS operadores_activos
    FROM tickets
")->queryOne();

// Distribución por operador
$operadores = Yii::$app->db->createCommand("
    SELECT u.nombre, COUNT(t.id) as cantidad, 
           SUM(CASE WHEN t.estado = 'cerrado' THEN 1 ELSE 0 END) as cerrados
    FROM tickets t
    JOIN usuario u ON t.id_operador = u.id
    GROUP BY u.nombre
    ORDER BY cantidad DESC
    LIMIT 5
")->queryAll();

// Evolución mensual
$mensual = Yii::$app->db->createCommand("
    SELECT 
        DATE_FORMAT(fecha_creacion, '%Y-%m') as mes,
        COUNT(*) as total,
        SUM(CASE WHEN estado = 'cerrado' THEN 1 ELSE 0 END) as cerrados
    FROM tickets
    WHERE fecha_creacion >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
    GROUP BY mes
    ORDER BY mes ASC
")->queryAll();

// Servicios más solicitados
$servicios = Yii::$app->db->createCommand("
    SELECT s.nombre, COUNT(t.id) as cantidad
    FROM tickets t
    JOIN servicio s ON t.id_servicio = s.id
    GROUP BY s.nombre
    ORDER BY cantidad DESC
    LIMIT 5
")->queryAll();

// Tickets recientes
$ticketsRecientes = Yii::$app->db->createCommand("
    SELECT t.*, s.nombre as servicio_nombre, 
           uc.correo as cliente_nombre,
           uo.correo as operador_nombre
    FROM tickets t
    JOIN servicio s ON t.id_servicio = s.id
    JOIN usuario uc ON t.id_cliente = uc.id
    JOIN usuario uo ON t.id_operador = uo.id
    ORDER BY t.fecha_creacion DESC
    LIMIT 7
")->queryAll();
?>

<div class="admin-dashboard">
    <h1><?= Html::encode($this->title) ?></h1>
    <p class="text-muted">Resumen global del sistema</p>
    
    <div class="row">
        <!-- Tarjeta 1: Tickets Totales -->
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Total Tickets</div>
                <div class="card-body">
                    <h1 class="card-title text-center"><?= $stats['total_tickets'] ?></h1>
                    <p class="card-text text-center">Registrados en el sistema</p>
                </div>
            </div>
        </div>
        
        <!-- Tarjeta 2: Tickets Abiertos -->
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Tickets Abiertos</div>
                <div class="card-body">
                    <h1 class="card-title text-center"><?= $stats['abiertos'] ?></h1>
                    <p class="card-text text-center">Pendientes de resolver</p>
                </div>
            </div>
        </div>
        
        <!-- Tarjeta 3: Tickets Cerrados -->
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Tickets Cerrados</div>
                <div class="card-body">
                    <h1 class="card-title text-center"><?= $stats['cerrados'] ?></h1>
                    <p class="card-text text-center">Resueltos satisfactoriamente</p>
                </div>
            </div>
        </div>
        
        <!-- Tarjeta 4: Usuarios Activos -->
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-header">Usuarios Activos</div>
                <div class="card-body">
                    <h4 class="card-title text-center">
                        <?= $stats['clientes_activos'] ?> Clientes<br>
                        <?= $stats['operadores_activos'] ?> Operadores
                    </h4>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <!-- Gráfico de Evolución Mensual -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Evolución Mensual</h5>
                    <small class="text-muted">Últimos 6 meses</small>
                </div>
                <div class="card-body">
                    <canvas id="evolucionChart" height="250"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Gráfico de Operadores -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Top 5 Operadores</h5>
                    <small class="text-muted">Por carga de trabajo</small>
                </div>
                <div class="card-body">
                    <canvas id="operadorChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <!-- Servicios más solicitados -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Servicios más Solicitados</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Servicio</th>
                                    <th>Tickets</th>
                                    <th>% Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($servicios as $servicio): ?>
                                <tr>
                                    <td><?= Html::encode($servicio['nombre']) ?></td>
                                    <td><?= $servicio['cantidad'] ?></td>
                                    <td>
                                        <?= number_format(($servicio['cantidad']/$stats['total_tickets'])*100, 1) ?>%
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Distribución de Estados -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Distribución Global de Estados</h5>
                </div>
                <div class="card-body">
                    <canvas id="estadoChart" height="250"></canvas>
                    <div class="mt-3 text-center">
                        <span class="badge bg-warning">Abiertos: <?= $stats['abiertos'] ?></span>
                        <span class="badge bg-info">En Progreso: <?= $stats['en_progreso'] ?></span>
                        <span class="badge bg-success">Cerrados: <?= $stats['cerrados'] ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Últimos Tickets -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Actividad Reciente</h5>
                    <div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>N° Serie</th>
                                    <th>Cliente</th>
                                    <th>Operador</th>
                                    <th>Servicio</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ticketsRecientes as $ticket): ?>
                                <tr>
                                    <td><?= Html::encode($ticket['n_serie']) ?></td>
                                    <td><?= Html::encode($ticket['cliente_nombre']) ?></td>
                                    <td><?= Html::encode($ticket['operador_nombre']) ?></td>
                                    <td><?= Html::encode($ticket['servicio_nombre']) ?></td>
                                    <td>
                                        <span class="badge 
                                            <?= $ticket['estado'] == 'cerrado' ? 'bg-success' : '' ?>
                                            <?= $ticket['estado'] == 'abierto' ? 'bg-warning' : '' ?>
                                            <?= $ticket['estado'] == 'en progreso' ? 'bg-info' : '' ?>
                                        ">
                                            <?= ucfirst($ticket['estado']) ?>
                                        </span>
                                    </td>
                                    <td><?= Yii::$app->formatter->asDate($ticket['fecha_creacion']) ?></td>
                                    <td>
                                        <a href="<?= Url::to(['tickets/view', 'id' => $ticket['id']]) ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i>
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
// Gráfico de evolución mensual
const evolucionCtx = document.getElementById('evolucionChart').getContext('2d');
new Chart(evolucionCtx, {
    type: 'line',
    data: {
        labels: <?= json_encode(array_column($mensual, 'mes')) ?>,
        datasets: [
            {
                label: 'Tickets Totales',
                data: <?= json_encode(array_column($mensual, 'total')) ?>,
                borderColor: '#36A2EB',
                backgroundColor: 'rgba(54, 162, 235, 0.1)',
                tension: 0.3,
                fill: true
            },
            {
                label: 'Tickets Cerrados',
                data: <?= json_encode(array_column($mensual, 'cerrados')) ?>,
                borderColor: '#4BC0C0',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                tension: 0.3,
                fill: true
            }
        ]
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

// Gráfico de operadores
const operadorCtx = document.getElementById('operadorChart').getContext('2d');
new Chart(operadorCtx, {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($operadores, 'nombre')) ?>,
        datasets: [
            {
                label: 'Tickets Asignados',
                data: <?= json_encode(array_column($operadores, 'cantidad')) ?>,
                backgroundColor: '#FFCE56'
            },
            {
                label: 'Tickets Cerrados',
                data: <?= json_encode(array_column($operadores, 'cerrados')) ?>,
                backgroundColor: '#4BC0C0'
            }
        ]
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
    type: 'doughnut',
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
        responsive: true,   
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>

<style>
.admin-dashboard {
    padding: 20px;
}

.card {
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    margin-bottom: 20px;
    border: none;
}

.card:hover {
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    transform: translateY(-2px);
}

.card-header {
    font-weight: bold;
    border-bottom: 1px solid rgba(0,0,0,0.1);
    padding: 15px 20px;
    background: rgba(0,0,0,0.03);
    border-radius: 10px 10px 0 0 !important;
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
    margin: 0 3px;
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

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
</style>
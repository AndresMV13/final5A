<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --accent: #4895ef;
            --dark: #1b263b;
            --light: #f8f9fa;
            --success: #4cc9f0;
            --warning: #f8961e;
            --danger: #f72585;
            --gray: #adb5bd;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            display: flex;
            min-height: 100vh;
            background-color: #f5f7fa;
            color: #333;
        }
        
        /* Sidebar */
        .sidebar {
            width: 250px;
            background: white;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
            z-index: 100;
        }
        
        .sidebar-header {
            padding: 20px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #eee;
        }
        
        .sidebar-header img {
            width: 40px;
            margin-right: 10px;
        }
        
        .sidebar-header h3 {
            font-size: 1.2rem;
            color: var(--dark);
        }
        
        .sidebar-menu {
            padding: 15px 0;
        }
        
        .menu-title {
            padding: 10px 20px;
            font-size: 0.8rem;
            text-transform: uppercase;
            color: var(--gray);
            font-weight: 600;
        }
        
        .menu-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            color: #555;
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }
        
        .menu-item:hover {
            background-color: #f8f9fa;
            color: var(--primary);
            border-left-color: var(--primary);
        }
        
        .menu-item.active {
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--primary);
            border-left-color: var(--primary);
        }
        
        .menu-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            padding: 20px;
            transition: all 0.3s;
        }
        
        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }
        
        .header-left h1 {
            font-size: 1.5rem;
            color: var(--dark);
        }
        
        .header-right {
            display: flex;
            align-items: center;
        }
        
        .search-bar {
            position: relative;
            margin-right: 20px;
        }
        
        .search-bar input {
            padding: 8px 15px 8px 35px;
            border: 1px solid #ddd;
            border-radius: 20px;
            width: 200px;
            outline: none;
            transition: all 0.3s;
        }
        
        .search-bar input:focus {
            border-color: var(--accent);
            width: 250px;
        }
        
        .search-bar i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            cursor: pointer;
        }
        
        .user-profile img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            margin-right: 10px;
        }
        
        .user-info h4 {
            font-size: 0.9rem;
            margin-bottom: 2px;
        }
        
        .user-info p {
            font-size: 0.7rem;
            color: var(--gray);
        }
        
        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
        
        .stat-icon.primary {
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--primary);
        }
        
        .stat-icon.success {
            background-color: rgba(76, 201, 240, 0.1);
            color: var(--success);
        }
        
        .stat-icon.warning {
            background-color: rgba(248, 150, 30, 0.1);
            color: var(--warning);
        }
        
        .stat-icon.danger {
            background-color: rgba(247, 37, 133, 0.1);
            color: var(--danger);
        }
        
        .stat-title {
            font-size: 0.9rem;
            color: var(--gray);
        }
        
        .stat-value {
            font-size: 1.8rem;
            font-weight: 600;
            margin: 5px 0;
        }
        
        .stat-change {
            font-size: 0.8rem;
            display: flex;
            align-items: center;
        }
        
        .stat-change.positive {
            color: #2ecc71;
        }
        
        .stat-change.negative {
            color: #e74c3c;
        }
        
        /* Charts Section */
        .charts-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .chart-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .chart-title {
            font-size: 1rem;
            font-weight: 600;
        }
        
        .chart-actions {
            display: flex;
        }
        
        .chart-actions select {
            padding: 5px 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            outline: none;
            font-size: 0.8rem;
        }
        
        .chart-placeholder {
            height: 250px;
            background-color: #f8f9fa;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray);
        }
        
        /* Recent Activity */
        .activity-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .activity-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .activity-title {
            font-size: 1rem;
            font-weight: 600;
        }
        
        .activity-list {
            list-style: none;
        }
        
        .activity-item {
            display: flex;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(67, 97, 238, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: var(--primary);
        }
        
        .activity-content {
            flex: 1;
        }
        
        .activity-message {
            font-size: 0.9rem;
            margin-bottom: 5px;
        }
        
        .activity-time {
            font-size: 0.8rem;
            color: var(--gray);
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .charts-container {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
                overflow: hidden;
            }
            
            .sidebar-header h3, .menu-title, .menu-item span {
                display: none;
            }
            
            .menu-item {
                justify-content: center;
                padding: 15px 0;
            }
            
            .menu-item i {
                margin-right: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="https://via.placeholder.com/40" alt="Logo">
            <h3>AdminPanel</h3>
        </div>
        
        <div class="sidebar-menu">
            <div class="menu-title">Principal</div>
            <a href="#" class="menu-item active">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-chart-line"></i>
                <span>Estadísticas</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-users"></i>
                <span>Usuarios</span>
            </a>
            
            <div class="menu-title">Gestión</div>
            <a href="#" class="menu-item">
                <i class="fas fa-ticket-alt"></i>
                <span>Tickets</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-boxes"></i>
                <span>Productos</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-file-invoice-dollar"></i>
                <span>Ventas</span>
            </a>
            
            <div class="menu-title">Configuración</div>
            <a href="#" class="menu-item">
                <i class="fas fa-cog"></i>
                <span>Ajustes</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-user-shield"></i>
                <span>Permisos</span>
            </a>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <h1>Panel de Control</h1>
            </div>
            <div class="header-right">
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Buscar...">
                </div>
                <div class="user-profile">
                    <img src="https://via.placeholder.com/35" alt="Usuario">
                    <div class="user-info">
                        <h4>Admin User</h4>
                        <p>Administrador</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-title">Usuarios Registrados</div>
                        <div class="stat-value">1,248</div>
                        <div class="stat-change positive">
                            <i class="fas fa-arrow-up"></i> 12% desde ayer
                        </div>
                    </div>
                    <div class="stat-icon primary">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-title">Tickets Activos</div>
                        <div class="stat-value">56</div>
                        <div class="stat-change negative">
                            <i class="fas fa-arrow-down"></i> 5% desde ayer
                        </div>
                    </div>
                    <div class="stat-icon warning">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-title">Ventas Hoy</div>
                        <div class="stat-value">$12,450</div>
                        <div class="stat-change positive">
                            <i class="fas fa-arrow-up"></i> 24% desde ayer
                        </div>
                    </div>
                    <div class="stat-icon success">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-title">Tasa de Satisfacción</div>
                        <div class="stat-value">92%</div>
                        <div class="stat-change positive">
                            <i class="fas fa-arrow-up"></i> 3% desde ayer
                        </div>
                    </div>
                    <div class="stat-icon danger">
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Charts Section -->
        <div class="charts-container">
            <div class="chart-card">
                <div class="chart-header">
                    <div class="chart-title">Ventas Mensuales</div>
                    <div class="chart-actions">
                        <select>
                            <option>Últimos 30 días</option>
                            <option>Últimos 90 días</option>
                            <option>Este año</option>
                        </select>
                    </div>
                </div>
                <div class="chart-placeholder">
                    Gráfico de ventas mensuales
                </div>
            </div>
            
            <div class="chart-card">
                <div class="chart-header">
                    <div class="chart-title">Distribución de Tickets</div>
                    <div class="chart-actions">
                        <select>
                            <option>Por estado</option>
                            <option>Por prioridad</option>
                        </select>
                    </div>
                </div>
                <div class="chart-placeholder">
                    Gráfico circular de tickets
                </div>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="activity-card">
            <div class="activity-header">
                <div class="activity-title">Actividad Reciente</div>
                <a href="#" style="font-size: 0.8rem; color: var(--primary);">Ver todo</a>
            </div>
            <ul class="activity-list">
                <li class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-message">Nuevo usuario registrado: María González</div>
                        <div class="activity-time">Hace 15 minutos</div>
                    </div>
                </li>
                <li class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-message">Ticket #4587 cerrado por Juan Pérez</div>
                        <div class="activity-time">Hace 1 hora</div>
                    </div>
                </li>
                <li class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-message">Nueva venta realizada por $1,250</div>
                        <div class="activity-time">Hace 3 horas</div>
                    </div>
                </li>
                <li class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-comment"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-message">Nuevo comentario en ticket #4582</div>
                        <div class="activity-time">Hace 5 horas</div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</body>
</html>
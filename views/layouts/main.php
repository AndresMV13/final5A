<?php
/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?> | <?= Yii::$app->name ?></title>
    <?php $this->head() ?>
    <style>
        :root {
            --primary-color: #6a0dad;
            --primary-dark: #4b0082;
            --secondary-color: #343a40;
            --accent-color: #9c27b0;
            --light-color: #f8f9fa;
        }
        .bg-primary-custom {
            background-color: var(--primary-color) !important;
        }
        .bg-primary-dark {
            background-color: var(--primary-dark) !important;
        }
        .text-primary-custom {
            color: var(--primary-color) !important;
        }
        .btn-primary-custom {
            background-color: var(--primary-color);
            border-color: var(--primary-dark);
        }
        .btn-primary-custom:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        .navbar-brand {
            font-weight: 700;
            letter-spacing: 1px;
        }
        .dropdown-menu {
            background-color: var(--secondary-color);
        }
        .dropdown-item {
            color: rgba(255,255,255,.75);
        }
        .dropdown-item:hover {
            background-color: var(--primary-dark);
            color: white;
        }
        body {
            background-color: #f5f5f5;
        }
        .main-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
            padding: 2rem;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        footer {
            background: linear-gradient(to right, var(--primary-dark), var(--secondary-color)) !important;
            color: white !important;
        }
        .nav-link {
            transition: all 0.3s ease;
        }
        .nav-link:hover {
            transform: translateY(-2px);
        }
        
        /* Estilos para navbar vertical a la izquierda */
        .navbar-vertical {
            height: 100vh;
            width: 250px;
            position: fixed;
            left: 0;
            top: 0;
            flex-direction: column;
            align-items: flex-start;
            padding: 1rem 0;
            box-shadow: 5px 0 15px rgba(0,0,0,0.1);
            z-index: 1030;
        }
        .navbar-vertical .navbar-nav {
            flex-direction: column;
            width: 100%;
        }
        .navbar-vertical .nav-item {
            width: 100%;
        }
        .navbar-vertical .nav-link {
            padding: 0.75rem 1.5rem;
        }
        .navbar-vertical .dropdown-menu {
            position: static !important;
            transform: none !important;
            width: 100%;
            border: none;
            box-shadow: none;
        }
        .main-content {
            margin-left: 250px; /* Ajuste para el navbar vertical */
            transition: margin-left 0.3s;
        }
        @media (max-width: 992px) {
            .navbar-vertical {
                width: 0;
                overflow: hidden;
                transition: width 0.3s;
            }
            .navbar-vertical.show {
                width: 250px;
            }
            .main-content {
                margin-left: 0;
            }
            .navbar-toggler-vertical {
                display: block;
                position: fixed;
                left: 15px;
                top: 15px;
                z-index: 1040;
            }
        }
    </style>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<!-- Botón toggler para móvil -->
<button class="navbar-toggler navbar-toggler-vertical d-lg-none btn btn-primary-custom" type="button" onclick="toggleNavbar()">
    <span class="navbar-toggler-icon"></span>
</button>

<!-- Navbar vertical a la izquierda -->
<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => Html::tag('span', Yii::$app->name, ['class' => 'text-white d-block text-center mb-4']),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-vertical navbar-dark bg-primary-dark shadow',
            'id' => 'verticalNavbar'
        ],
    ]);
    
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav w-100'],
        'items' => [
            ['label' => 'Inicio', 'url' => ['/site/index'], 'linkOptions' => ['class' => 'nav-link']],
            
            // Menú desplegable para Servicios
            !Yii::$app->user->isGuest && (Yii::$app->user->identity->validateAccess('servicio') || Yii::$app->user->identity->hasRole(3))
            ? [
                'label' => 'Servicios <i class="bi bi-chevron-down float-end"></i>',
                'items' => [
                    !Yii::$app->user->isGuest && Yii::$app->user->identity->hasRole(3)
                    ? ['label' => 'Contratar Servicios', 'url' => ['/servicio/contratar-servicio']] : '',
                    !Yii::$app->user->isGuest && Yii::$app->user->identity->hasRole(3)
                    ? ['label' => 'Calificar Servicios', 'url' => ['/calificacion/create']] : '',
                    !Yii::$app->user->isGuest && Yii::$app->user->identity->validateAccess('servicio')
                    ? ['label' => 'Administrar Servicios', 'url' => ['/servicio/index']] : '',
                ],
                'options' => ['class' => 'nav-item dropdown'],
                'linkOptions' => ['class' => 'nav-link dropdown-toggle', 'role' => 'button', 'data-bs-toggle' => 'dropdown'],
                'dropdownOptions' => ['class' => 'dropdown-menu'],
                'encode' => false,
            ] : '',
            
            // Menú desplegable para Administración
            !Yii::$app->user->isGuest && Yii::$app->user->identity->validateAccess('roles')
            ? [
                'label' => 'Administración <i class="bi bi-chevron-down float-end"></i>',
                'items' => [
                    ['label' => 'Roles', 'url' => ['/rol/index']],
                    ['label' => 'Usuarios', 'url' => ['/usuario/index']],
                    ['label' => 'Asistencias', 'url' => ['/asistencia/index']],
                ],
                'options' => ['class' => 'nav-item dropdown'],
                'linkOptions' => ['class' => 'nav-link dropdown-toggle', 'role' => 'button', 'data-bs-toggle' => 'dropdown'],
                'dropdownOptions' => ['class' => 'dropdown-menu'],
                'encode' => false,
            ] : '',
            
            !Yii::$app->user->isGuest && Yii::$app->user->identity->hasRole(3)
            ? ['label' => 'Mis Tickets', 'url' => ['/tickets/my-tickets'], 'linkOptions' => ['class' => 'nav-link']] : '',
            !Yii::$app->user->isGuest && Yii::$app->user->identity->hasRole(2)
            ? ['label' => 'Tickets Asignados', 'url' => ['/tickets/my-tickets'], 'linkOptions' => ['class' => 'nav-link']] : '',
            !Yii::$app->user->isGuest && Yii::$app->user->identity->hasRole(1)
            ? ['label' => 'Tickets', 'url' => ['/tickets/my-tickets'], 'linkOptions' => ['class' => 'nav-link']] : '',
            
            Yii::$app->user->isGuest 
                ? ['label' => 'Iniciar Sesión', 'url' => ['/site/login'], 'linkOptions' => ['class' => 'nav-link mt-auto']]
                : [
                    'label' => 'Cerrar Sesión (' . Yii::$app->user->identity->info() . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => [
                        'class' => 'nav-link mt-auto',
                        'data-method' => 'post'
                    ],
                    'encode' => false
                ],
        ],
        'dropdownClass' => 'yii\bootstrap5\Dropdown',
    ]);
    NavBar::end();
    ?>
</header>

<!-- Contenido principal -->
<div class="main-content flex-shrink-0" id="main">
    <div class="container-fluid py-4">
        <div class="container main-container">
            <?php if (!empty($this->params['breadcrumbs'])): ?>
                <?= Breadcrumbs::widget([
                    'links' => $this->params['breadcrumbs'],
                    'options' => ['class' => 'breadcrumb bg-light p-3 rounded'],
                    'itemTemplate' => '<li class="breadcrumb-item">{link}</li>',
                    'activeItemTemplate' => '<li class="breadcrumb-item active">{link}</li>'
                ]) ?>
            <?php endif ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </div>
</div>

<footer id="footer" class="mt-auto py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                    <span class="fs-5 fw-bold me-2"><?= Yii::$app->name ?></span>
                    <span class="text-white-50">&copy; <?= date('Y') ?> Todos los derechos reservados</span>
                </div>
            </div>
            <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                <div class="social-links">
                    <a href="#" class="text-white me-3"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white me-3"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="text-white me-3"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-white"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
        </div>
    </div>
</footer>

<script>
    function toggleNavbar() {
        const navbar = document.getElementById('verticalNavbar');
        navbar.classList.toggle('show');
    }
    
    // Cerrar el navbar al hacer clic fuera en móviles
    document.addEventListener('click', function(event) {
        const navbar = document.getElementById('verticalNavbar');
        const toggler = document.querySelector('.navbar-toggler-vertical');
        
        if (window.innerWidth <= 992 && 
            !navbar.contains(event.target) && 
            event.target !== toggler && 
            !toggler.contains(event.target)) {
            navbar.classList.remove('show');
        }
    });
</script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
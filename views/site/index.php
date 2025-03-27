<?php
/** @var yii\web\View $this */

$this->title = 'Codex Nenis - Suscripciones Gaming';
?>
<style>
    :root {
        --ps-blue: #003087; /* PlayStation azul */
        --xbox-green: #107c10; /* Xbox verde */
        --nintendo-red: #e60012; /* Nintendo rojo */
        --dark-bg: #0f0f13; /* Fondo oscuro */
        --light-text: #f8f9fa;
        --card-bg: #1a1a23;
    }
    
    .gaming-hero {
        background: linear-gradient(135deg, #2a0845 0%, #6441A5 100%); /* Gradiente gamer */
        color: white;
        padding: 5rem 0;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        position: relative;
        overflow: hidden;
        margin-bottom: 3rem;
        text-align: center;
    }
    
    .gaming-hero::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path fill="rgba(255,255,255,0.03)" d="M0,0 L100,0 L100,100 L0,100 Z" /></svg>');
        opacity: 0.1;
    }
    
    .display-4 {
        font-weight: 800;
        margin-bottom: 1.5rem;
        position: relative;
        text-shadow: 0 2px 10px rgba(0,0,0,0.7);
        font-family: 'Arial Black', sans-serif;
    }
    
    .gaming-lead {
        font-size: 1.4rem;
        margin-bottom: 2.5rem;
        position: relative;
        color: rgba(255,255,255,0.9);
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .btn-gaming {
        background: #6441A5;
        border: none;
        padding: 0.9rem 2.8rem;
        font-size: 1.1rem;
        font-weight: 700;
        border-radius: 8px;
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        color: white;
        text-transform: uppercase;
    }
    
    .btn-gaming:hover {
        transform: translateY(-3px);
        box-shadow: 0 7px 20px rgba(0,0,0,0.4);
        background: #7d5bbe;
        color: white;
    }
    
    .service-card {
        background: var(--card-bg);
        border-radius: 12px;
        padding: 2rem;
        height: 100%;
        box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        transition: all 0.4s ease;
        position: relative;
        overflow: hidden;
        border-top: 4px solid;
        color: white;
    }
    
    .service-card.ps {
        border-color: var(--ps-blue);
    }
    
    .service-card.xbox {
        border-color: var(--xbox-green);
    }
    
    .service-card.nintendo {
        border-color: var(--nintendo-red);
    }
    
    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.4);
    }
    
    .service-card h2 {
        margin-bottom: 1.5rem;
        font-weight: 700;
        font-size: 1.8rem;
    }
    
    .service-icon {
        font-size: 3.5rem;
        margin-bottom: 1.5rem;
    }
    
    .ps .service-icon {
        color: var(--ps-blue);
    }
    
    .xbox .service-icon {
        color: var(--xbox-green);
    }
    
    .nintendo .service-icon {
        color: var(--nintendo-red);
    }
    
    .service-price {
        font-size: 2rem;
        font-weight: 700;
        margin: 1.5rem 0;
    }
    
    .btn-service {
        background: transparent;
        border: 2px solid;
        padding: 0.7rem 1.5rem;
        font-weight: 600;
        border-radius: 6px;
        transition: all 0.3s ease;
        width: 100%;
        margin-top: 1rem;
    }
    
    .btn-service.ps {
        border-color: var(--ps-blue);
        color: var(--ps-blue);
    }
    
    .btn-service.xbox {
        border-color: var(--xbox-green);
        color: var(--xbox-green);
    }
    
    .btn-service.nintendo {
        border-color: var(--nintendo-red);
        color: var(--nintendo-red);
    }
    
    .btn-service:hover {
        background: white;
        transform: translateY(-2px);
    }
    
    .features-list {
        list-style: none;
        padding: 0;
        margin: 1.5rem 0;
    }
    
    .features-list li {
        padding: 0.5rem 0;
        position: relative;
        padding-left: 1.8rem;
    }
    
    .features-list li:before {
        content: "✓";
        position: absolute;
        left: 0;
        font-weight: bold;
    }
    
    .ps .features-list li:before {
        color: var(--ps-blue);
    }
    
    .xbox .features-list li:before {
        color: var(--xbox-green);
    }
    
    .nintendo .features-list li:before {
        color: var(--nintendo-red);
    }
    
    .stats-section {
        background-color: var(--dark-bg);
        color: white;
        padding: 3rem 0;
        border-radius: 15px;
        margin: 4rem 0;
        text-align: center;
    }
    
    .stat-item {
        text-align: center;
        padding: 1.5rem;
    }
    
    .stat-number {
        font-size: 3rem;
        font-weight: 700;
        color: #6441A5;
        margin-bottom: 0.5rem;
    }
    
    .section-title {
        color: white;
        margin-bottom: 3rem;
        font-weight: 700;
        text-align: center;
        font-size: 2rem;
    }
    
    .platform-logos {
        display: flex;
        justify-content: center;
        gap: 2rem;
        margin-bottom: 3rem;
        flex-wrap: wrap;
    }
    
    .platform-logo {
        height: 60px;
        opacity: 0.8;
        transition: all 0.3s ease;
    }
    
    .platform-logo:hover {
        opacity: 1;
        transform: scale(1.1);
    }
</style>

<div class="site-index">

    <div class="gaming-hero">
        <div class="container" style="position: relative; z-index: 1;">
            <h1 class="display-4">MEJORA TU EXPERIENCIA GAMER</h1>
            
            <p class="gaming-lead">Adquiere las mejores suscripciones para PlayStation, Xbox y Nintendo al mejor precio del mercado</p>
            
            <p><a class="btn btn-gaming" href="/servicio/contratar-servicio">COMPRAR AHORA</a></p>
        </div>
    </div>

    <div class="body-content">

        <div class="platform-logos">
            <img src="https://upload.wikimedia.org/wikipedia/commons/0/00/PlayStation_logo.svg" alt="PlayStation" class="platform-logo">
            <img src="https://upload.wikimedia.org/wikipedia/commons/f/f9/Xbox_one_logo.svg" alt="Xbox" class="platform-logo">
            <img src="https://upload.wikimedia.org/wikipedia/commons/0/0d/Nintendo.svg" alt="Nintendo" class="platform-logo">
        </div>

        <h2 class="section-title">SUSCRIPCIONES DISPONIBLES</h2>

        <div class="row mb-5">
            <div class="col-lg-4 mb-4">
                <div class="service-card ps">
                    <div class="service-icon">
                        <i class="bi bi-playstation"></i>
                    </div>
                    <h2>PlayStation Plus</h2>
                    <p>Acceso a juegos mensuales, multijugador online y descuentos exclusivos.</p>
                    
                    <ul class="features-list">
                        <li>Juegos mensuales gratis</li>
                        <li>Multijugador online</li>
                        <li>Almacenamiento en la nube</li>
                        <li>Descuentos exclusivos</li>
                    </ul>
                    
                    <div class="service-price">$9.99/mes</div>
                    
                    <a class="btn btn-service ps" href="#">CONTRATAR</a>
                </div>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="service-card xbox">
                    <div class="service-icon">
                        <i class="bi bi-xbox"></i>
                    </div>
                    <h2>Xbox Game Pass</h2>
                    <p>Biblioteca con más de 100 juegos de alta calidad para consola y PC.</p>
                    
                    <ul class="features-list">
                        <li>Más de 100 juegos</li>
                        <li>Juegos nuevos el día de lanzamiento</li>
                        <li>Para consola y PC</li>
                        <li>Descuentos en compras</li>
                    </ul>
                    
                    <div class="service-price">$14.99/mes</div>
                    
                    <a class="btn btn-service xbox" href="#">CONTRATAR</a>
                </div>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="service-card nintendo">
                    <div class="service-icon">
                        <i class="bi bi-nintendo-switch"></i>
                    </div>
                    <h2>Nintendo Online</h2>
                    <p>Juega online, accede a clásicos de NES y SNES, y disfruta de funciones exclusivas.</p>
                    
                    <ul class="features-list">
                        <li>Juegos online</li>
                        <li>Colección de juegos clásicos</li>
                        <li>Aplicación Nintendo Switch Online</li>
                        <li>Copias de seguridad en la nube</li>
                    </ul>
                    
                    <div class="service-price">$3.99/mes</div>
                    
                    <a class="btn btn-service nintendo" href="#">CONTRATAR</a>
                </div>
            </div>
        </div>
        
        <div class="stats-section">
            <div class="container">
                <h2 class="section-title">¿POR QUÉ ELEGIRNOS?</h2>
                <div class="row">
                    <div class="col-md-4 stat-item">
                        <div class="stat-number">100%</div>
                        <p>Cuentas Oficiales</p>
                    </div>
                    <div class="col-md-4 stat-item">
                        <div class="stat-number">24/7</div>
                        <p>Soporte Técnico</p>
                    </div>
                    <div class="col-md-4 stat-item">
                        <div class="stat-number">+10K</div>
                        <p>Clientes Satisfechos</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
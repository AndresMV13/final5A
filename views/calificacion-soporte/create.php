<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\CalificacionSoporte $model */
/** @var app\models\Tickets $modelTicket */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="calificacion-soporte-form">

    <h1>N. Serie: <?= Html::encode($modelTicket->n_serie) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <!-- Mostrar información del ticket -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Información del Ticket</h5>
            <p class="card-text">
                <strong>Número de Serie:</strong> <?= Html::encode($modelTicket->n_serie) ?><br>
                <strong>Usuario:</strong> <?= Html::encode($modelTicket->id_cliente) ?><br>
                <strong>Operador:</strong> <?= Html::encode($modelTicket->id_operador) ?>
            </p>
        </div>
    </div>

    <!-- Campos de calificación -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Calificación del Servicio</h5>

            <div class="rating-section mb-4">
                <label class="rating-label">1. ¿Cómo calificaría la atención recibida?</label>
                <div class="star-rating">
                    <?= $form->field($modelCalificacion, 'p1', ['template' => '{input}'])->hiddenInput(['id' => 'p1-rating'])->label(false) ?>
                    <div class="stars" data-target="#p1-rating">
                        <span class="star" data-value="1">★</span>
                        <span class="star" data-value="2">★</span>
                        <span class="star" data-value="3">★</span>
                        <span class="star" data-value="4">★</span>
                        <span class="star" data-value="5">★</span>
                    </div>
                    <span class="rating-value ml-2"></span>
                </div>
            </div>

            <div class="rating-section mb-4">
                <label class="rating-label">2. ¿El problema se resolvió satisfactoriamente?</label>
                <div class="star-rating">
                    <?= $form->field($modelCalificacion, 'p2', ['template' => '{input}'])->hiddenInput(['id' => 'p2-rating'])->label(false) ?>
                    <div class="stars" data-target="#p2-rating">
                        <span class="star" data-value="1">★</span>
                        <span class="star" data-value="2">★</span>
                        <span class="star" data-value="3">★</span>
                        <span class="star" data-value="4">★</span>
                        <span class="star" data-value="5">★</span>
                    </div>
                    <span class="rating-value ml-2"></span>
                </div>
            </div>

            <div class="rating-section mb-4">
                <label class="rating-label">3. ¿El tiempo de respuesta fue adecuado?</label>
                <div class="star-rating">
                    <?= $form->field($modelCalificacion, 'p3', ['template' => '{input}'])->hiddenInput(['id' => 'p3-rating'])->label(false) ?>
                    <div class="stars" data-target="#p3-rating">
                        <span class="star" data-value="1">★</span>
                        <span class="star" data-value="2">★</span>
                        <span class="star" data-value="3">★</span>
                        <span class="star" data-value="4">★</span>
                        <span class="star" data-value="5">★</span>
                    </div>
                    <span class="rating-value ml-2"></span>
                </div>
            </div>

            <div class="rating-section mb-4">
                <label class="rating-label">4. ¿El trato del operador fue adecuado?</label>
                <div class="star-rating">
                    <?= $form->field($modelCalificacion, 'p4', ['template' => '{input}'])->hiddenInput(['id' => 'p4-rating'])->label(false) ?>
                    <div class="stars" data-target="#p4-rating">
                        <span class="star" data-value="1">★</span>
                        <span class="star" data-value="2">★</span>
                        <span class="star" data-value="3">★</span>
                        <span class="star" data-value="4">★</span>
                        <span class="star" data-value="5">★</span>
                    </div>
                    <span class="rating-value ml-2"></span>
                </div>
            </div>

            <div class="rating-section mb-4">
                <label class="rating-label">5. ¿Recomendaría nuestro servicio?</label>
                <div class="star-rating">
                    <?= $form->field($modelCalificacion, 'p5', ['template' => '{input}'])->hiddenInput(['id' => 'p5-rating'])->label(false) ?>
                    <div class="stars" data-target="#p5-rating">
                        <span class="star" data-value="1">★</span>
                        <span class="star" data-value="2">★</span>
                        <span class="star" data-value="3">★</span>
                        <span class="star" data-value="4">★</span>
                        <span class="star" data-value="5">★</span>
                    </div>
                    <span class="rating-value ml-2"></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Campos ocultos para el ID del operador y el número de serie -->
    <?= $form->field($modelCalificacion, 'id_operador')->hiddenInput(['value' => $modelTicket->id_operador])->label(false) ?>
    <?= $form->field($modelCalificacion, 'numero_serie')->hiddenInput(['value' => $modelTicket->n_serie])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar Calificación', ['class' => 'btn btn-primary btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<style>
    .rating-section {
        padding: 15px;
        border-radius: 8px;
        background-color: #f8f9fa;
    }
    
    .rating-label {
        display: block;
        margin-bottom: 10px;
        font-weight: 500;
        color: #333;
    }
    
    .star-rating {
        display: flex;
        align-items: center;
    }
    
    .stars {
        display: inline-block;
        font-size: 24px;
        line-height: 1;
    }
    
    .star {
        color: #ddd;
        cursor: pointer;
        transition: color 0.2s;
        margin-right: 2px;
    }
    
    .star:hover,
    .star.active {
        color: #ffc107;
    }
    
    .rating-value {
        font-size: 16px;
        font-weight: 500;
        color: #666;
    }
    
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }
    
    .card-title {
        color: #2c3e50;
        font-weight: 600;
    }
    
    .btn-primary {
        background-color: #3498db;
        border-color: #3498db;
        padding: 10px 25px;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar todos los sistemas de estrellas
    document.querySelectorAll('.stars').forEach(starsContainer => {
        const hiddenInput = document.querySelector(starsContainer.getAttribute('data-target'));
        const ratingValue = starsContainer.nextElementSibling;
        const stars = starsContainer.querySelectorAll('.star');
        
        // Actualizar visualización de estrellas
        function updateStars(value) {
            stars.forEach(star => {
                if (star.dataset.value <= value) {
                    star.classList.add('active');
                } else {
                    star.classList.remove('active');
                }
            });
            ratingValue.textContent = value + '/5';
        }
        
        // Establecer valor inicial si existe
        if (hiddenInput.value) {
            updateStars(hiddenInput.value);
        }
        
        // Manejar clic en estrellas
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const value = this.dataset.value;
                hiddenInput.value = value;
                updateStars(value);
            });
            
            // Efecto hover
            star.addEventListener('mouseover', function() {
                const hoverValue = this.dataset.value;
                stars.forEach(s => {
                    if (s.dataset.value <= hoverValue) {
                        s.style.color = '#ffc107';
                    } else {
                        s.style.color = '#ddd';
                    }
                });
            });
            
            star.addEventListener('mouseout', function() {
                stars.forEach(s => {
                    s.style.color = hiddenInput.value >= s.dataset.value ? '#ffc107' : '#ddd';
                });
            });
        });
    });
});
</script>
<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm; 
use app\models\UsuarioServicio;
/** @var yii\web\View $this */
/** @var app\models\Calificacion $model */

$this->title = 'Calificar mis servicios';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
?>
<div class="calificacion-create">

    <h1 class="text-center mb-4"><?= Html::encode($this->title) ?></h1>

    <div class="card shadow-sm p-4" style="max-width: 600px; margin: 0 auto;">
        <?php $form = ActiveForm::begin(['options' => ['class' => 'rating-form']]); ?>
        
        <?= $form->field($model, 'id_usuario')->hiddenInput(['value'=>Yii::$app->user->identity->id])->label(false) ?>
        
        <?= $form->field($model, 'id_servicio')->dropDownList(
            \yii\helpers\ArrayHelper::map(
                $serviciosContratados,
                'id_servicio',
                function ($servicio) { return $servicio->servicio->nombre; }
            ),
            [
                'prompt' => 'Selecciona un servicio',
                'class' => 'form-select'
            ]
        )->label('Servicio a calificar') ?>
        
        <div class="form-group mb-4">
            <label class="form-label">Calificación</label>
            <div class="rating-stars">
                <?php for ($i = 5; $i >= 1; $i--): ?>
                    <input type="radio" id="star<?= $i ?>" name="Calificacion[calificacion]" value="<?= $i ?>" <?= $model->calificacion == $i ? 'checked' : '' ?>>
                    <label for="star<?= $i ?>">
                        <i class="fas fa-star"></i>
                    </label>
                <?php endfor; ?>
                <div class="clearfix"></div>

            </div>
            <?= Html::error($model, 'calificacion', ['class' => 'text-danger']) ?>
        </div>

        <div class="form-group text-center">
            <?= Html::submitButton('Guardar Calificación', ['class' => 'btn btn-primary btn-lg']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<style>
    .rating-stars {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
    }
    
    .rating-stars input {
        display: none;
    }
    
    .rating-stars label {
        color: #ddd;
        font-size: 2rem;
        padding: 0 3px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .rating-stars input:checked ~ label,
    .rating-stars input:hover ~ label {
        color: #ffc107;
    }
    
    .rating-stars label:hover,
    .rating-stars label:hover ~ label {
        color: #ffc107;
    }
    
    .rating-stars input:checked + label {
        color: #ffc107;
    }
    
    .clearfix {
        clear: both;
    }
    
    .card {
        border-radius: 15px;
    }
    
    .form-select {
        padding: 10px;
        border-radius: 8px;
    }
</style>

<?php
// Registra el JavaScript para mejorar la interacción
$this->registerJs(<<<JS
    $(document).ready(function() {
        // Cambia el texto según la selección
        $('.rating-stars input').on('change', function() {
            const rating = $(this).val();
            let text = '';
            
            switch(rating) {
                case '1': text = 'Malo'; break;
                case '2': text = 'Regular'; break;
                case '3': text = 'Bueno'; break;
                case '4': text = 'Muy bueno'; break;
                case '5': text = 'Excelente'; break;
                default: text = 'Selecciona de 1 a 5 estrellas';
            }
            
            $('.rating-text small').text(text);
        });
    });
JS);
?>
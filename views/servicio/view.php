<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Servicio $model */

$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Servicios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$dataProvider = $model->getUsuariosAQ();
$dataProvider2 = $model->getCalificacionsAQ();

?>
<div class="servicio-view container mt-4">

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h1 class="card-title mb-0"><?= Html::encode($this->title) ?> -  $<?= Html::encode($model->precio) ?></h1>
        </div>
        <div class="card-body">
            <p class="lead text-muted"><?= Html::encode($model->descripcion) ?></p>
            
            <?php if (Yii::$app->user->identity->rol->nombre == 'Administrador'): ?>
                <p class="mb-4">
                    <?= Html::a('Actualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                </p>
            <?php endif; ?>

            <?= DetailView::widget([
                'model' => $model,
                'options' => ['class' => 'table table-striped table-bordered detail-view'],
                'attributes' => [
                    // Aquí puedes agregar tus atributos si necesitas mostrar más datos
                ],
            ]) ?>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white">
            <h2 class="h5 mb-0">Usuarios asociados</h2>
        </div>
        <div class="card-body">
            <?= $this->render('_users', ['dataProvider' => $dataProvider]) ?>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-warning text-white">
            <h2 class="h5 mb-0">Calificaciones</h2>
        </div>
        <div class="card-body text-center">
            <h3 class="h4 mb-3">Promedio de calificaciones</h3>
            <?php 
                $promedio = $model->promedio($model->id); 
                function stars($promedio) {
                    $stars = intval($promedio);
                    $starHtml = '';
                    for ($i = 0; $i < 5; $i++) {
                        if ($i < $stars) {
                            $starHtml .= '<span class="text-warning" style="font-size: 2.5rem;">&#9733;</span>';
                        } else {
                            $starHtml .= '<span class="text-secondary" style="font-size: 2.5rem;">&#9734;</span>'; 
                        }
                    }
                    return $starHtml;
                }
                echo '<div class="mb-3">' . stars($promedio) . '</div>';
                echo '<p class="h5">' . number_format($promedio, 1) . ' / 5.0</p>';
            ?>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h2 class="h5 mb-0">Reseñas</h2>
        </div>
        <div class="card-body">
            <?= $this->render('_rate', ['dataProvider' => $dataProvider2]) ?>
        </div>
    </div>

</div>
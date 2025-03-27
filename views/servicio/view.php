<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Servicio $model */

$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Servicios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$dataProvider=$model->getUsuariosAQ();
$dataProvider2=$model->getCalificacionsAQ();

?>
<div class="servicio-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

    </p>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
        ],
    ]) ?>
    <?= $this->render('_users',['dataProvider'=>$dataProvider]) ?>
    <h2>Promedio</h2>
    <?php 
        $promedio = $model->promedio($model->id); 
        function stars($promedio) {
            $stars = intval($promedio);
            $starHtml = '';
            for ($i = 0; $i < 5; $i++) {
                if ($i < $stars) {
                    $starHtml .= '<span style="color: gold; font-size:64px;">&#9733;</span>';
                } else {
                    $starHtml .= '<span style="color: lightgray; font-size:64px;">&#9734;</span>'; 
                }
            }
            return $starHtml;
        }
    echo stars($promedio);
    ?>
    <?= $this->render('_rate',['dataProvider'=>$dataProvider2]) ?>

</div>

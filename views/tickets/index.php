<?php
use app\models\Tickets;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\ListView;

/** @var yii\web\View $this */
/** @var app\models\TicketsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Tickets';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tickets-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="tickets-container">
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_ticket_card',
            'layout' => "<div class='row'>{items}</div>\n{pager}",
            'itemOptions' => ['class' => 'col-lg-4 col-md-6 mb-4'],
            'options' => ['class' => 'tickets-grid'],
        ]); ?>
    </div>

</div>

<style>
    .tickets-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }
    
    /* Opcional: Estilos responsivos adicionales */
    @media (max-width: 992px) {
        .tickets-container .col-lg-4 {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }
    
    @media (max-width: 768px) {
        .tickets-container .col-md-6 {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }
    
    /* Asegurar que las cards tengan la misma altura */
    .tickets-container .card {
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .tickets-container .card-body {
        flex-grow: 1;
    }
</style>
<?php
use yii\widgets\ListView;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Mis Tickets';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="tickets-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Yii::$app->user->identity->rol->nombre == 'Cliente' 
            ? Html::a('Nuevo Ticket', ['levantar-ticket'], ['class' => 'btn btn-success']) 
            : '' 
        ?>
    </p>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_ticket_card_my_tickets', // Nueva vista parcial
        'layout' => "<div class='row'>{items}</div>\n{pager}", // Organizar en fila Bootstrap
        'itemOptions' => ['class' => 'col-md-4 mb-4'], // Espaciado entre cards
    ]); ?>
</div>

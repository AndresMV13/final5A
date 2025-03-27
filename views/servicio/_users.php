<?php
use yii\grid\GridView;
use tii\data\ActiveDataProvider;
use app\models\User;

/** @var yii\data\ActiveDataProvider $dataProvider */

?>

<h3>Suscriptores:</h3>
<?= GridView::widget([
    'dataProvider'=>$dataProvider,
    'columns'=>[
            'nombre',
            'correo',
        ],
    ]);?>
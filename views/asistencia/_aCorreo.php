<?php
use yii\grid\GridView;
use tii\data\ActiveDataProvider;
use app\models\Asistencia;

/** @var yii\data\ActiveDataProvider $dataProvider */

?>

<h3>Acceso a:</h3>
<?= GridView::widget([
    'dataProvider'=>$dataProvider,
    'columns'=>[
            ['class'=>'yii\grid\SerialColumn'],
            'correo',
            'entrada',
            'salida',
            
        ],
    ]);?>
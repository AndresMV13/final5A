<?php
use yii\grid\GridView;
use tii\data\ActiveDataProvider;
use app\models\User;

/** @var yii\data\ActiveDataProvider $dataProvider */

?>

<h3>Usuarios con este rol:</h3>
<?= GridView::widget([
    'dataProvider'=>$dataProvider,
    'columns'=>[
            ['class'=>'yii\grid\SerialColumn'],
            'nombre',
            'apellido_paterno',
            'correo',
        ],
    ]);?>
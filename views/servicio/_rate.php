<?php
use yii\grid\GridView;
use tii\data\ActiveDataProvider;
use app\models\Calificacion;

/** @var yii\data\ActiveDataProvider $dataProvider */

?>

<h3>Calificaciones:</h3>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'attribute' => 'calificacion',
            'format' => 'raw',
            'value' => function ($model) {
                $stars = intval($model->calificacion);
                $starHtml = '';
                for ($i = 0; $i < 5; $i++) {
                    if ($i < $stars) {
                        $starHtml .= '<span style="color: gold; font-size:26px;">&#9733;</span>';
                    } else {
                        $starHtml .= '<span style="color: lightgray; font-size:26px;">&#9734;</span>'; 
                    }
                }
                return $starHtml;
            }
        ],
    ],
]);
?>
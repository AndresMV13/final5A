<?php

use app\models\Servicio;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

?>
<?php foreach ($servicios as $servicio): ?>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><?= Html::encode($servicio->nombre) ?></h5>

            <?php
            $usuarioServicio = \app\models\UsuarioServicio::findOne([
                'id_usuario' => Yii::$app->user->id,
                'id_servicio' => $servicio->id
            ]);
            ?>

            <?php if ($usuarioServicio): ?>
                <?php if ($usuarioServicio->estatus == 'activo'): ?>
                    <?= Html::a('Cancelar', ['contratar', 'id' => $servicio->id], ['class' => 'btn btn-danger']) ?>
                <?php else: ?>
                    <?= Html::a('Volver a contratar', ['contratar', 'id' => $servicio->id], ['class' => 'btn btn-success']) ?>
                <?php endif; ?>
            <?php else: ?>
                <?= Html::a('Contratar', ['contratar', 'id' => $servicio->id], ['class' => 'btn btn-primary']) ?>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>

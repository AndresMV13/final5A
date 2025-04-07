<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\User;
use app\models\Horario;
use yii\helpers\Url;

$this->title = 'Asignar Horario a Operador';
?>

<style>
    .assignment-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 30px;
        background: #f8f9fa;
        border-radius: 8px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    }
    
    .assignment-header {
        text-align: center;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eaeaea;
    }
    
    .assignment-header h1 {
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 10px;
    }
    
    .form-container {
        background: white;
        padding: 25px;
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .form-group {
        margin-bottom: 25px;
    }
    
    .form-control {
        height: 45px;
        border-radius: 4px;
        border: 1px solid #ddd;
        box-shadow: none;
    }
    
    .form-control:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 0.2rem rgba(52,152,219,.25);
    }
    
    .btn-assign {
        background-color: #3498db;
        border-color: #2980b9;
        padding: 10px 25px;
        font-size: 16px;
        font-weight: 500;
        border-radius: 4px;
        width: 100%;
        transition: all 0.3s;
    }
    
    .btn-assign:hover {
        background-color: #2980b9;
        transform: translateY(-2px);
    }
    
    .panel-title {
        font-weight: 600;
        color: #2c3e50;
    }
    
    .horario-panel {
        margin-bottom: 25px;
        border-radius: 6px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        border: none;
    }
    
    .panel-heading {
        background-color: #f8f9fa !important;
        border-bottom: 1px solid #eaeaea !important;
        border-top-left-radius: 6px !important;
        border-top-right-radius: 6px !important;
    }
    
    .panel-info > .panel-heading {
        color: #2c3e50;
        background-color: #f8f9fa;
        border-color: #eaeaea;
    }
    
    .table th {
        background-color: #f8f9fa;
        color: #2c3e50;
        font-weight: 500;
    }
    
    .label-success {
        background-color: #2ecc71;
        padding: 5px 10px;
        border-radius: 3px;
        font-weight: 500;
    }
    
    .alert-warning {
        background-color: #fcf8e3;
        border-color: #faebcc;
        color: #8a6d3b;
        border-radius: 4px;
    }
    
    @media (max-width: 768px) {
        .assignment-container {
            padding: 15px;
        }
    }
</style>

<div class="assignment-container">
    <div class="assignment-header">
        <h1><?= Html::encode($this->title) ?></h1>
        <p class="text-muted">Asigne horarios de trabajo a los operadores del sistema</p>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="form-container">
                <?php $form = ActiveForm::begin([
                    'id' => 'form-asignar-horario',
                    'action' => Url::to(['usuario/guardar-horario']),
                    'method' => 'post',
                    'options' => ['class' => 'form-horizontal']
                ]); ?>

                <div class="form-group">
                    <?= $form->field(new User(), 'id', [
                        'labelOptions' => ['class' => 'control-label', 'style' => 'font-weight:500']
                    ])->dropDownList(
                        ArrayHelper::map(User::find()->where(['id_rol'=>'2'])->all(), 'id', 'correo'),
                        [
                            'prompt' => 'Seleccione un operador',
                            'class' => 'form-control select2'
                        ]
                    ) ?>
                </div>

                <div class="form-group">
                    <?= $form->field(new Horario(), 'id', [
                        'labelOptions' => ['class' => 'control-label', 'style' => 'font-weight:500']
                    ])->dropDownList(
                        ArrayHelper::map(Horario::find()->all(), 'id', 'dias','turno'),
                        [
                            'prompt' => 'Seleccione un horario',
                            'class' => 'form-control select2'
                        ]
                    ) ?>
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Asignar Horario', [
                        'class' => 'btn btn-primary btn-assign',
                        'style' => 'margin-top:10px'
                    ]) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
    
    <div class="row" style="margin-top:30px">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title" style="font-size:18px">
                        <i class="glyphicon glyphicon-time"></i> Operadores Activos por Horario
                    </h3>
                </div>
                <div class="panel-body">
                    <?php 
                    $horarios = Horario::find()
                        ->orderBy('turno, dias')
                        ->all();

                    foreach ($horarios as $horario): 
                        $operadores = $horario->getOperadoresActivos()->all();
                    ?>
                        <div class="panel panel-info horario-panel">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <strong><?= Html::encode(ucfirst($horario->turno)) ?></strong> - 
                                    <?= Html::encode(str_replace('-', ' a ', $horario->dias)) ?>
                                    <span class="pull-right">
                                        <i class="glyphicon glyphicon-time"></i> 
                                        <?= substr($horario->hora_entrada_esperada, 0, 5) ?> - 
                                        <?= substr($horario->hora_salida_esperada, 0, 5) ?>
                                    </span>
                                </h4>
                            </div>
                            <div class="panel-body">
                                <?php if (!empty($operadores)): ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th width="30%">Operador</th>
                                                    <th width="35%">Correo</th>
                                                    <th width="35%">Asignado desde</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($operadores as $asignacion): 
                                                    $usuario = $asignacion->usuario;
                                                ?>
                                                    <tr>
                                                        <td>
                                                            <i class="glyphicon glyphicon-user"></i> 
                                                            <?= Html::encode($usuario->nombre . ' ' . $usuario->apellido_paterno) ?>
                                                        </td>
                                                        <td><?= Html::encode($usuario->correo) ?></td>
                                                        <td>
                                                            <?= Yii::$app->formatter->asDate($asignacion->fecha_asignacion) ?>
                                                            <span class="label label-success pull-right">
                                                                <i class="glyphicon glyphicon-ok"></i> Activo
                                                            </span>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-warning text-center">
                                        <i class="glyphicon glyphicon-info-sign"></i> No hay operadores asignados a este horario
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Registrar CSS adicional
$this->registerCss('
.select2-container--default .select2-selection--single {
    height: 45px !important;
    padding: 10px 16px;
    border: 1px solid #ddd !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 43px !important;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 23px !important;
}
');
?>

<?php
// Registrar JS para Select2
$this->registerJs('
$(".select2").select2({
    theme: "bootstrap",
    placeholder: "Seleccione una opción",
    allowClear: true,
    width: "100%"
});
', \yii\web\View::POS_READY);
?>
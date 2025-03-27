<?php

namespace app\controllers;
use Yii;
use app\models\Rol;
use app\models\Permisos;
use app\models\RolSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RolController implements the CRUD actions for Rol model.
 */
class RolController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Rol models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new RolSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Rol model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Rol model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Rol();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                // Guardar las secciones seleccionadas
                if (!empty($model->seccionesSeleccionadas)) {
                    foreach ($model->seccionesSeleccionadas as $id_seccion) {
                        $permiso = new Permisos(); // Usar "Permisos" en lugar de "Permiso"
                        $permiso->id_rol = $model->id;
                        $permiso->id_seccion = $id_seccion;
                        $permiso->estatus = 'activo';
                        if (!$permiso->save()) {
                            Yii::error('Error al guardar el permiso: ' . print_r($permiso->errors, true));
                        }
                    }
                }

                Yii::$app->session->setFlash('success', 'Rol creado correctamente.');
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::error('Error al guardar el rol: ' . print_r($model->errors, true));
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    /**
     * Updates an existing Rol model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
{
    $model = $this->findModel($id); 

    if ($this->request->isPost && $model->load($this->request->post())) {
        
        if ($model->save()) {
            
            $permisosExistentes = Permisos::find()
                ->where(['id_rol' => $model->id])
                ->indexBy('id_seccion') 
                ->all();

            
            if (!empty($model->seccionesSeleccionadas)) {
                foreach ($model->seccionesSeleccionadas as $id_seccion) {
                    if (isset($permisosExistentes[$id_seccion])) {
                        
                        $permiso = $permisosExistentes[$id_seccion];
                        $permiso->estatus = 'activo'; 
                        $permiso->save();
                    } else {
                        
                        $permiso = new Permisos();
                        $permiso->id_rol = $model->id;
                        $permiso->id_seccion = $id_seccion;
                        $permiso->estatus = 'activo';
                        $permiso->save();
                    }
                }
            }
            if (!is_array($model->seccionesSeleccionadas)) {
                $model->seccionesSeleccionadas = explode(',', $model->seccionesSeleccionadas);}
            // Desactivar permisos no seleccionados
            foreach ($permisosExistentes as $id_seccion => $permiso) {
                if (!in_array($id_seccion, $model->seccionesSeleccionadas)) {
                    $permiso->estatus = 'inactivo'; // Desactivar el permiso
                    $permiso->save();
                }
            }

            Yii::$app->session->setFlash('success', 'Rol actualizado correctamente.');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            Yii::error('Error al guardar el rol: ' . print_r($model->errors, true));
        }
    }

    
    $model->seccionesSeleccionadas = $model->getPermisosActive()
        ->select('id_seccion')
        ->column();

    return $this->render('update', [
        'model' => $model,
    ]);
}

    /**
     * Deletes an existing Rol model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Rol model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Rol the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Rol::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

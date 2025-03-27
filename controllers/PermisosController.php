<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Rol;
use app\models\Seccion;
use app\models\Permisos;


class PermisosController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionAsignar($id_rol){
        $rol=Rol::finOne($id_rol);
        if(!$rol){
            throw new \yii\web\NotFoundHttpException('El Rol no existe');
        }
        $secciones = Seccion::find()->all();
        if (Yii::$app->request->isPost){
            $seccionesSeleccionadas= Yii::$app->request->post('secciones',[]);
        Permisos::deleteAll(['id_rol'=>$id_rol]);
        foreach($seccionesSeleccionadas as $id_seccion){
            $permiso = new Permiso();
            $permiso->id_rol=$id_rol;
            $permiso->id_seccion=$id_seccion;
            $permiso->save();
        }
        Yii::$app->session->setFlash('success', 'Permisos actualizados correctamente.');
        return $this->redirect(['view', 'id' => $id_rol]);
    }
    return $this->render('asignar', [
        'rol' => $rol,
        'secciones' => $secciones,
    ]);
    }

    public function actionView($id_rol)
    {
        $rol = Rol::findOne($id_rol);
        if (!$rol) {
            throw new \yii\web\NotFoundHttpException('El rol no existe.');
        }
        return $this->render('view', [
            'rol' => $rol,
        ]);
    }
}



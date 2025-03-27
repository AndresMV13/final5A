<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use Pusher\Pusher;
use yii\web\Response;
use app\models\Mensajes;
use app\models\Tickets;

class ChatController extends Controller
{
    public function actionSendMessage()
    {Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $request = Yii::$app->request;
        $data = json_decode($request->rawBody, true); // Decodificar JSON
    
        if (!$data || !isset($data['ticket_id'], $data['remitente'], $data['contenido'])) {
            return ['success' => false, 'error' => 'Datos incompletos'];
        }
    
        // Insertar en la base de datos (Ejemplo usando ActiveRecord)
        $mensaje = new Mensajes();
        $mensaje->id_ticket = $data['ticket_id'];
        $mensaje->id_usuario = $data['remitente'];
        $mensaje->mensaje = $data['contenido'];

        $buevito=$mensaje->mensaje;
    
        if ($mensaje->save()) {
            $pusher = new Pusher('26fa7bcc6c794d3f5f5d', 'bfd1cf9825bcd6f829d9', '1955169', 
            ['cluster' => 'us2']);

            $data = [
                'id_ticket' => $mensaje->id_ticket,
                'id_usuario' => $mensaje->id_usuario,
                'mensaje' => $buevito
            ];
            $pusher->trigger('chat-channel', 'nuevo-mensaje', $data);
            return ['success' => true, 'message' => 'Mensaje guardado'];
        } else {
            return ['success' => false, 'error' => 'Error al guardar'];
        }
        if ($mensaje->save()) {
            // Enviar el mensaje a Pusher
            

            return ['success' => true, 'mensaje' => $data];
        }
/*
        if ($request->isPost) {
            $ticket_id = $request->post('ticket_id');
            $remitente = $request->post('remitente');
            $contenido = $request->post('contenido');

            if (!$ticket_id || !$remitente || !$contenido) {
                return ['success' => false, 'error' => 'Datos incompletos'];
            }

            // Guardar el mensaje en la base de datos
            $mensaje = new Mensajes();
            $mensaje->id_ticket = $ticket_id;
            $mensaje->id_usuario = $remitente;
            $mensaje->mensaje = $contenido;

            if ($mensaje->save()) {
                // Enviar el mensaje a Pusher
                $options = Yii::$app->params['pusher'];
                $pusher = new Pusher($options['key'], $options['secret'], $options['app_id'], [
                    'cluster' => $options['cluster'],
                    'useTLS' => $options['useTLS']
                ]);

                $data = [
                    'ticket_id' => $ticket_id,
                    'remitente' => $remitente,
                    'contenido' => $contenido
                ];
                $pusher->trigger('chat-channel', 'nuevo-mensaje', $data);

                return ['success' => true, 'mensaje' => $data];
            }

            return ['success' => false, 'error' => $mensaje->getErrors()];
        }

        return ['success' => false, 'error' => 'Método no permitido'];
        */
    }
    
    public function actionGetMessages($id_ticket)
    {
        $mensajes = Mensajes::find()
        ->where(['id_ticket' => $id_ticket])
        ->orderBy('fecha_envio ASC') // Ordenar por fecha de envío
        ->all();
        return $this->render('index', [
            'mensajes' => $mensajes,
            'id_ticket' => $id_ticket,
            'id_usuario' => Yii::$app->user->id, 
        ]);
    }

    public function actionIndex($id){
        $model = Tickets::findOne($id);
        $mensajes = Mensajes::find()
        ->where(['id_ticket' => $id])
        ->orderBy('fecha ASC') // Ordenar por fecha de envío
        ->all();
        $id_usuario= Yii::$app->user->identity->id;
        return $this->render('index',[
            'mensajes'=>$mensajes,
            'model'=>$model,
            'id_usuario'=>$id_usuario
        ]
    );

        
    }
}

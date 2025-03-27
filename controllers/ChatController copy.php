<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\Mensajes;
use Pusher\Pusher;

/**
 * Controlador encargado de manejar la funcionalidad del chat en la aplicación.
 */
class ChatController extends Controller
{
    public function actionSendMessage()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;

        if ($request->isPost) {
            $ticket_id = $request->post('ticket_id');
            $remitente = $request->post('remitente');
            $contenido = $request->post('contenido');

            // Guardar el mensaje en la base de datos
            $mensaje = new Mensaje();
            $mensaje->ticket_id = $ticket_id;
            $mensaje->remitente = $remitente;
            $mensaje->contenido = $contenido;
            $mensaje->fecha = date('Y-m-d H:i:s');

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
    }

    
    public function actionIndex(){
        return $this->render('index');

        
    }
}



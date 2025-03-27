
<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap5\Modal;

?>

<!--Aqui va todo el pinche chat-->
<div id="chat-container" class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div id="mensajes" class="chat-box">
                <?php foreach ($mensajes as $mensaje): ?>
                    <?php if ($mensaje->id_usuario == $id_usuario): ?>
                        <!-- Mensaje del usuario en sesión (derecha) -->
                        <div class="message-container user-message">
                            <div class="message-bubble user">
                                <p><?= Html::encode($mensaje->mensaje) ?></p>
                                <small class="text-muted"><?= Yii::$app->formatter->asDatetime($mensaje->fecha) ?></small>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Mensaje del otro usuario (izquierda) -->
                        <div class="message-container other-message">
                            <div class="message-bubble other">
                                <p><?= Html::encode($mensaje->mensaje) ?></p>
                                <small class="text-muted"><?= Yii::$app->formatter->asDatetime($mensaje->fecha) ?></small>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <!-- Área de texto y botón para enviar mensajes -->
            <div class="input-group mt-3">
                <textarea id="contenido" class="form-control" placeholder="Escribe tu mensaje..." rows="2"></textarea>
                <div class="input-group-append">
                    <button class="btn btn-primary" onclick="enviarMensaje()">Enviar</button>
                </div>
            </div>
            <div class="text-right mt-3">
                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmarCerrarTicketModal">
                    Cerrar Ticket
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Cachitos de labio donde me besaste Cachitos de mano que tú me tomaste Si mi corazón no deja de latir Me voy a encontrar cachitos de ti
Cachitos de llanto, por tanto llorarte Cachitos de vida que tú me quitaste Aunque el corazón me deje de latir Me van a enterrar con cachitos de ti-->


<!-- Modal de Confirmación para Cerrar Ticket -->
<div class="modal fade" id="confirmarCerrarTicketModal" tabindex="-1" role="dialog" aria-labelledby="confirmarCerrarTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmarCerrarTicketModalLabel">¿Cerrar Ticket?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas cerrar este ticket? Una vez cerrado, no podrás enviar más mensajes.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" onclick="cerrarTicket(ticketId)">Cerrar Ticket</button>
            </div>
        </div>
    </div>
</div>
<!--De esos cachitos está llena mi libreta Estás aquí dentro aunque ya no estés allí afuera Están porque estás, 
están porque estabas Todavía eres todo aunque ya no somos nada-->


                    <!--Ponle canciones depres pa saber que ahi va la logica, aqui los scripts gracias-->
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
    window.onload = function() {
    const mensajes = document.getElementById('mensajes');
    mensajes.scrollTop = mensajes.scrollHeight;
};
    const ticketId = <?php echo $model->id; ?>;
    const userId = <?= $id_usuario ?>; 
    
    // Inicializar Pusher
    Pusher.logToConsole=true;
    const pusher = new Pusher('26fa7bcc6c794d3f5f5d', {
        cluster: 'us2',
        encrypted: true
    });
    
    //Escuchar el canal en el que estabamos mandando los mensajes
    const channel = pusher.subscribe('chat-channel');

    //Atrapar los mensajes en tiempo real pa
    channel.bind('nuevo-mensaje', function(data) {
        if (data.id_ticket == ticketId) {
            const mensajes = document.getElementById('mensajes');
            const mensajeClass = (data.id_usuario == userId) ? 'user-message' : 'other-message';
            const bubbleClass = (data.id_usuario == userId) ? 'user' : 'other';

            const nuevoMensaje = `
                <div class="message-container ${mensajeClass}">
                    <div class="message-bubble ${bubbleClass}">
                        <p>${data.mensaje}</p>
                        <small class="text-muted">${new Date().toLocaleString()}</small>
                    </div>
                </div>
            `;

            mensajes.innerHTML += nuevoMensaje;
            mensajes.scrollTop = mensajes.scrollHeight;
        }
    });

    function enviarMensaje() {
    const contenido = document.getElementById('contenido').value;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('http://localhost:8080/index.php?r=chat/send-message', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': csrfToken // Agregar CSRF Token
        },
        body: JSON.stringify({ 
            ticket_id: ticketId,
            remitente: <?php echo $id_usuario; ?>,
            contenido: contenido
        })
    })
    .then(response => response.text())  // Captura la respuesta completa
    .then(text => {
        console.log("Respuesta del servidor:", text);
        try {
            const data = JSON.parse(text);
            if (data.success) {
                console.log("Mensaje enviado correctamente");
            } else {
                console.error("Error al enviar mensaje:", data.error);
            }
        } catch (error) {
            console.error("No se pudo parsear JSON. Respuesta:", text);
        }
    })
    .catch(error => console.error("Error en la petición:", error));

    document.getElementById('contenido').value = ''; // Limpiar el campo
}

function cerrarTicket(ticketId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch(`http://localhost:8080/index.php?r=tickets/cerrar-ticket&id=${ticketId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({}) // No es necesario enviar el ticket_id en el cuerpo
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                throw new Error(`Error en la respuesta del servidor: ${response.status} - ${text}`);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Construir la URL de redirección
            let redirectUrl = `http://localhost:8080/index.php?r=${data.redirect}`;
            // Si hay un ID, agregarlo a la URL
            if (data.id) {
                redirectUrl += `&id=${data.id}`;
            }
            window.location.href = redirectUrl;
        } else {
            alert("Error al cerrar el ticket: " + (data.error || 'Error desconocido'));
        }
    })
    .catch(error => {
        console.error("Error en la petición:", error);
        alert("Error en la petición: " + error.message);
    });
}


</script>


<style>
    /* Contenedor del chat */
    .chat-box {
        height: 500px;
        overflow-y: auto;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 15px;
        background-color: #f9f9f9;
    }

    /* Contenedor de cada mensaje */
    .message-container {
        display: flex;
        margin-bottom: 15px;
    }

    /* Mensajes del usuario en sesión (derecha) */
    .user-message {
        justify-content: flex-end;
    }

    /* Mensajes del otro usuario (izquierda) */
    .other-message {
        justify-content: flex-start;
    }

    /* Burbuja de mensaje */
    .message-bubble {
        max-width: 70%;
        padding: 10px 15px;
        border-radius: 15px;
        position: relative;
    }

    /* Estilo para los mensajes del usuario en sesión */
    .message-bubble.user {
        background-color: #007bff;
        color: white;
        border-bottom-right-radius: 5px;
    }

    /* Estilo para los mensajes del otro usuario */
    .message-bubble.other {
        background-color: #e9ecef;
        color: black;
        border-bottom-left-radius: 5px;
    }

    /* Estilo para el texto de la fecha */
    .text-muted {
        font-size: 0.8em;
        display: block;
        margin-top: 5px;
    }

    /* Área de texto y botón */
    .input-group {
        margin-top: 15px;
    }

    .input-group textarea {
        resize: none;
    }

    .input-group-append button {
        height: 100%;
    }
</style>

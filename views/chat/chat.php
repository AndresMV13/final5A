<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div id="chat-box" style="height: 300px; overflow-y: scroll; border: 1px solid #ccc; padding: 10px;"></div>
<input type="text" id="chat-message" placeholder="Escribe tu mensaje...">
<button id="send-message">pene</button>

<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
    const pusher = new Pusher('26fa7bcc6c794d3f5f5d', {
        cluster: 'us2',
        encrypted: true
    });

    const channel = pusher.subscribe('chat-channel');
    channel.bind('new-message', function(data) {
        const chatBox = document.getElementById('chat-box');
        const messageElement = document.createElement('div');
        messageElement.innerHTML = `<strong>${data.user}:</strong> ${data.message}`;
        chatBox.appendChild(messageElement);
        chatBox.scrollTop = chatBox.scrollHeight;
    });

    document.getElementById('send-message').addEventListener('click', function() {
        const message = document.getElementById('chat-message').value;
        fetch('<?= Url::to(['chat/send-message']) ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': '<?= Yii::$app->request->csrfToken ?>'
            },
            body: JSON.stringify({ message: message })
        }).then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('chat-message').value = '';
              }
          });
    });
</script>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversación</title>
    <link rel="stylesheet" href="/public/css/theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Ajustes para la ventana de chat */
        .chat-window {
            flex-grow: 1;
            overflow-y: scroll;
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }

        
        /* Estilo para los mensajes */
        .message {
            margin-bottom: 20px;
            width: 100%;
        }
        .message .message-text {
            display: inline-block;
            max-width: 75%;
            padding: 10px;
            border-radius: 8px;
            word-wrap: break-word;
            margin-bottom: 10px;
        }

        .message.user1 .message-text {
            background-color: var(--bg-aux);
            float: left;
        }

        .message.user2 .message-text {
            background-color: var(--primary-color);
            color: white;
            float: right;
        }

        
        .header-chat {
            display: flex;
            justify-content: start;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding: 15px 0;
        }

        .header-chat .btn-back {
            font-size: 14px;
        }

        .header-chat img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

    </style>
</head>
<body>

<div class="container p-0 h-100 d-flex flex-column">
    <!-- Cabecera de la conversación -->
    <div class="header-chat">
        <a href="/views/students/chats/index.php" class="btn btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0"/>
            </svg>
        </a>
        <div class="d-flex align-items-center">
            <img src="https://via.placeholder.com/40" alt="Usuario 1" class="me-3">
            <div>
                <div><strong>Usuario 1</strong></div>
                <small class="text-muted text-sm">Última vez activo: 2 horas</small>
            </div>
        </div>
    </div>

    <!-- Ventana de chat -->
    <div class="chat-window">
        <!-- Mensaje del usuario 1 -->
        <div class="message user1">
            <div class="message-text">
                ¡Hola, ¿cómo estás?
            </div>
        </div>

        <!-- Mensaje del usuario 2 -->
        <div class="message user2">
            <div class="message-text">
                Todo bien, gracias. ¿Y tú?
            </div>
        </div>

        <!-- Mensaje del usuario 1 -->
        <div class="message user1">
            <div class="message-text">
                Genial, estoy trabajando en un nuevo proyecto. Te contaré más pronto.
            </div>
        </div>

        <!-- Mensaje del usuario 2 -->
        <div class="message user2">
            <div class="message-text">
                ¡Qué emocionante! Avísame cuando tengas novedades.
            </div>
        </div>
    </div>

    <!-- Caja de texto para escribir mensaje -->
    <div class="message-input">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Escribe un mensaje...">
            <button class="btn bg-custom-primary text-white" type="button">Enviar</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

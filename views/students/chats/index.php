<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Principal</title>
    <link rel="stylesheet" href="/public/css/theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .chat-list {
            height: 100vh;
            overflow-y: auto;
        }
        .chat-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #ddd;
            cursor: pointer;
        }
        
        .chat-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .chat-details {
            flex-grow: 1;
        }
        .chat-name {
            font-weight: bold;
            text-decoration: none;
            color: var(--text);
        }
        .chat-message {
            font-size: 0.85em;
            color: #555;
        }
        .last-message {
            color: #888;
        }
    </style>
</head>
<body>

<div class="container-fluid d-flex p-0" style="height: 100vh;">
    <!-- Lista de Chats -->
    <div class="col-12 chat-list">
    <ul class="list-group list-unstyled">
    <li class="chat-item">
        <a href="/views/students/chats/chat.php?id=1" class="d-flex align-items-center text-decoration-none">
            <img src="https://via.placeholder.com/50" alt="Usuario 1" class="chat-img me-3">
            <div class="chat-details">
                <div class="chat-name">Usuario 1</div>
                <div class="chat-message last-message">¡Hola! ¿Cómo estás?</div>
            </div>
        </a>
    </li>
    <li class="chat-item">
        <a href="/views/students/chats/chat.php?id=2" class="d-flex align-items-center text-decoration-none">
            <img src="https://via.placeholder.com/50" alt="Usuario 2" class="chat-img me-3">
            <div class="chat-details">
                <div class="chat-name">Usuario 2</div>
                <div class="chat-message last-message">Nos vemos mañana a las 10.</div>
            </div>
        </a>
    </li>
    <li class="chat-item">
        <a href="/views/students/chats/chat.php?id=3" class="d-flex align-items-center text-decoration-none">
            <img src="https://via.placeholder.com/50" alt="Usuario 3" class="chat-img me-3">
            <div class="chat-details">
                <div class="chat-name">Usuario 3</div>
                <div class="chat-message last-message">¡Genial! Lo haré más tarde.</div>
            </div>
        </a>
    </li>
    <li class="chat-item">
        <a href="/views/students/chats/chat.php?id=4" class="d-flex align-items-center text-decoration-none">
            <img src="https://via.placeholder.com/50" alt="Usuario 4" class="chat-img me-3">
            <div class="chat-details">
                <div class="chat-name">Usuario 4</div>
                <div class="chat-message last-message">¿Cómo te fue en la reunión?</div>
            </div>
        </a>
    </li>
</ul>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

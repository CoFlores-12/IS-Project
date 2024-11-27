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
    <ul class="list-group list-unstyled" id="chatsContainer">
    <li class="chat-item">
        <a href="/views/students/chats/chat.php?id=1" class="d-flex w-full align-items-center text-decoration-none">
            <img src="https://via.placeholder.com/50" alt="Usuario 1" class="chat-img me-3">
            <div class="chat-details w-full">
                <p class=" w-full placeholder-glow mb-0">
                    <span class="placeholder bg-secondary col-4"></span>
                    <span class="placeholder bg-secondary col-12"></span>
                </p>
            </div>
        </a>
    </li>
    <li class="chat-item">
        <a href="/views/students/chats/chat.php?id=1" class="d-flex w-full align-items-center text-decoration-none">
            <img src="https://via.placeholder.com/50" alt="Usuario 1" class="chat-img me-3">
            <div class="chat-details w-full">
                <p class=" w-full placeholder-glow mb-0">
                    <span class="placeholder bg-secondary col-4"></span>
                    <span class="placeholder bg-secondary col-12"></span>
                </p>
            </div>
        </a>
    </li>
    <li class="chat-item">
        <a href="/views/students/chats/chat.php?id=1" class="d-flex w-full align-items-center text-decoration-none">
            <img src="https://via.placeholder.com/50" alt="Usuario 1" class="chat-img me-3">
            <div class="chat-details w-full">
                <p class=" w-full placeholder-glow mb-0">
                    <span class="placeholder bg-secondary col-4"></span>
                    <span class="placeholder bg-secondary col-12"></span>
                </p>
            </div>
        </a>
    </li>
    <li class="chat-item">
        <a href="/views/students/chats/chat.php?id=1" class="d-flex w-full align-items-center text-decoration-none">
            <img src="https://via.placeholder.com/50" alt="Usuario 1" class="chat-img me-3">
            <div class="chat-details w-full">
                <p class=" w-full placeholder-glow mb-0">
                    <span class="placeholder bg-secondary col-4"></span>
                    <span class="placeholder bg-secondary col-12"></span>
                </p>
            </div>
        </a>
    </li>
    
</ul>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const chatsContainer = document.getElementById('chatsContainer')
    fetch('/api/get/chats/chats.php')
    .then((res)=>{return res.json()})
    .then((res)=>{
        if (res.status) {
            chatsContainer.innerHTML = '';
            res.data.forEach(chat => {
                let name = chat.is_group == 1 ? chat.group_name : chat.direct_user_name
                chatsContainer.innerHTML += `<li class="chat-item">
                    <a href="/views/chats/chat.php?id=${chat.chat_id}" class="d-flex align-items-center text-decoration-none">
                        <img src="https://via.placeholder.com/50" alt="Usuario 4" class="chat-img me-3">
                        <div class="chat-details">
                            <div class="chat-name">${name}</div>
                            <div class="chat-message last-message">${chat.last_message === null ? '': chat.last_message}</div>
                        </div>
                    </a>
                </li>`
            });
        }
    })
    .catch(err =>{
        chatsContainer.innerHTML = `<div class="alert alert-danger m-4" role="alert">
  No se puede acceder a los chats en este momento, vuelva a intentarlo mas tarde.   
</div>`
    })
</script>
</body>
</html>

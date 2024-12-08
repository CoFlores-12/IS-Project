<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Principal</title>
    <link rel="stylesheet" href="/public/css/theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="/public/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <style>
        *::-webkit-scrollbar {
        width: 7px;
        display: block !important;
        }
        
        *::-webkit-scrollbar-track {
        background: var(--bg);
        }
        
        *::-webkit-scrollbar-thumb {
        background: var(--primary-color);
        border-radius: 1rem;
        }
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
        .badge-dot {
        border-radius: 50%;
        height: 10px;
        width: 10px;
        margin-left: 2.9rem;
        margin-top: -.75rem;
        }
        img {
                    width: 50px;
                    height: 50px;
                    border-radius: 50%;
                }

        a {
            text-decoration: none;
        }
        .text-muted {
                    color: var( --secondary-color) !important;
                }
        .msgContent> * {
            text-overflow: ellipsis; 
        overflow: hidden; 
        white-space: nowrap;
        max-width: 100%;
        width: 100%;
        }
        .msgContent {
            width: calc(100vw - 150px - .5rem);
        }

        #chatsContainer > li > a  {
            max-width: 100%;
            overflow: hidden;
        }
        #chatsContainer > li > a > div {
            max-width: 100%;
            overflow: hidden;
            width: 100% !important;
        }
        #chatsContainer > li > a > div > .msgContent {
            display: block;
        flex-grow: 1;
        flex-shrink: 2;
        }
    </style>
</head>
<body>


<!-- Modal -->
<!-- Modal de Solicitudes de Contacto -->
<div class="modal fade" id="contactsModal" tabindex="-1" aria-labelledby="requestsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content bg">
      <div class="modal-header">
        <h5 class="modal-title" id="requestsModalLabel">Solicitudes de Contacto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Pestañas -->
<ul class="nav nav-tabs" id="requestsTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active bg-aux text" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="true">Contactos</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link bg-aux text" id="sent-tab" data-bs-toggle="tab" data-bs-target="#sent" type="button" role="tab" aria-controls="sent" aria-selected="false">Enviadas</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link bg-aux text" id="received-tab" data-bs-toggle="tab" data-bs-target="#received" type="button" role="tab" aria-controls="received" aria-selected="false">Recibidas</button>
    </li>
</ul>

<!-- Contenido de las Pestañas -->
<div class="tab-content" id="requestsTabsContent">
    <div class="tab-pane fade show active table-responsive" id="contact" role="tabpanel" aria-labelledby="contact-tab">
        <table id="contacts" class="table mt-3 table-striped table-hover">
            <thead>
                <tr>
                    <th class="bg-aux text">Estudiante</th>
                    <th class="bg-aux text">Correo</th>
                    <th class="bg-aux text">Telefono</th>
                </tr>
            </thead>
            <tbody>
                <!-- Las filas de solicitudes pendientes se llenarán aquí -->
            </tbody>
        </table>
    </div>
    <div class="tab-pane fade table-responsive" id="sent" role="tabpanel" aria-labelledby="sent-tab">
        <table id="pendingRequestsTable" class="table mt-3 table-striped table-hover">
            <thead>
                <tr>
                    <th class="bg-aux text">Estudiante</th>
                    <th class="bg-aux text">Número de cuenta</th>
                </tr>
            </thead>
            <tbody>
                <!-- Las filas de solicitudes enviadas se llenarán aquí -->
            </tbody>
        </table>
    </div>
    <div class="tab-pane fade table-responsive" id="received" role="tabpanel" aria-labelledby="received-tab">
        <table id="receivedRequestsTable" class="table mt-3 table-striped table-hover">
            <thead>
                <tr>
                    <th class="bg-aux text">Estudiante</th>
                    <th class="bg-aux text">Correo</th>
                    <th class="bg-aux text">Telefono</th>
                    <th class="bg-aux text">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Las filas de solicitudes recibidas se llenarán aquí -->
            </tbody>
        </table>
    </div>
</div>

      </div>
    </div>
  </div>
</div>





<div id="chatsV" style="position: relative">
    <div class="input-group rounded mb-3 p-3">
        <input type="Buscar" class="form-control rounded" placeholder="Buscar" aria-label="Buscar"
        aria-describedby="search-addon" />
        <span class="input-group-text text bg-aux border-0" id="search-addon">
        <i class="bi bi-search"></i>
        </span>
    </div>
    <ul class="list-unstyled mb-0" id="chatsContainer">
        <li class="p-2 border-bottom">
            <a href="#!" class="d-flex justify-content-between">
            <div class="d-flex flex-row flex-1">
                <div>
                <img
                    src="https://via.placeholder.com/50"
                    alt="avatar" class="d-flex align-self-center me-3" width="60">
                <span class="badge bg-success badge-dot"></span>
                </div>
                <div class="pt-1 w-full">
                    <p class=" w-full placeholder-glow">
                        <span class="placeholder col-7  bg-dark"></span>
                        <span class="placeholder col-12 bg-dark"></span>
                    </p>
                </div>
            </div>
            </a>
        </li>
        <li class="p-2 border-bottom">
            <a href="#!" class="d-flex justify-content-between">
            <div class="d-flex flex-row flex-1">
                <div>
                <img
                    src="https://via.placeholder.com/50"
                    alt="avatar" class="d-flex align-self-center me-3" width="60">
                <span class="badge bg-success badge-dot"></span>
                </div>
                <div class="pt-1 w-full">
                    <p class=" w-full placeholder-glow">
                        <span class="placeholder col-7  bg-dark"></span>
                        <span class="placeholder col-12 bg-dark"></span>
                    </p>
                </div>
            </div>
            </a>
        </li>
        <li class="p-2 border-bottom">
            <a href="#!" class="d-flex justify-content-between">
            <div class="d-flex flex-row flex-1">
                <div>
                <img
                    src="https://via.placeholder.com/50"
                    alt="avatar" class="d-flex align-self-center me-3" width="60">
                <span class="badge bg-success badge-dot"></span>
                </div>
                <div class="pt-1 w-full">
                    <p class=" w-full placeholder-glow">
                        <span class="placeholder col-7  bg-dark"></span>
                        <span class="placeholder col-12 bg-dark"></span>
                    </p>
                </div>
            </div>
            </a>
        </li>
        <li class="p-2 border-bottom">
            <a href="#!" class="d-flex justify-content-between">
            <div class="d-flex flex-row flex-1">
                <div>
                <img
                    src="https://via.placeholder.com/50"
                    alt="avatar" class="d-flex align-self-center me-3" width="60">
                <span class="badge bg-success badge-dot"></span>
                </div>
                <div class="pt-1 w-full">
                    <p class=" w-full placeholder-glow">
                        <span class="placeholder col-7  bg-dark"></span>
                        <span class="placeholder col-12 bg-dark"></span>
                    </p>
                </div>
            </div>
            </a>
        </li>
        <li class="p-2 border-bottom">
            <a href="#!" class="d-flex justify-content-between">
            <div class="d-flex flex-row flex-1">
                <div>
                <img
                    src="https://via.placeholder.com/50"
                    alt="avatar" class="d-flex align-self-center me-3" width="60">
                <span class="badge bg-success badge-dot"></span>
                </div>
                <div class="pt-1 w-full">
                    <p class=" w-full placeholder-glow">
                        <span class="placeholder col-7  bg-dark"></span>
                        <span class="placeholder col-12 bg-dark"></span>
                    </p>
                </div>
            </div>
            </a>
        </li>
        <li class="p-2 border-bottom">
            <a href="#!" class="d-flex justify-content-between">
            <div class="d-flex flex-row flex-1">
                <div>
                <img
                    src="https://via.placeholder.com/50"
                    alt="avatar" class="d-flex align-self-center me-3" width="60">
                <span class="badge bg-success badge-dot"></span>
                </div>
                <div class="pt-1 w-full">
                    <p class=" w-full placeholder-glow">
                        <span class="placeholder col-7  bg-dark"></span>
                        <span class="placeholder col-12 bg-dark"></span>
                    </p>
                </div>
            </div>
            </a>
        </li>
    </ul>
</div> 
    <div class="dropup position-fixed bottom-0 end-0 m-4">
    <button type="button" id="btnViewContac"  class="btn  flex justify-center items-center text-white btn-lg hide-toggle" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor"  id="requestsBTN" class="bi bi-plus bg-custom-primary rounded-circle" viewBox="0 0 16 16">
    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
</svg>
  </button>
  <ul class="dropdown-menu">
    <li>
      <a class="dropdown-item" href="#">Contactos</a>
    </li>
  </ul>
</div>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function getTimeElapsed(messageTime) {
        if (messageTime === null) {
            return ''
        }
        const now = new Date();
        const messageDate = new Date(messageTime);
        const elapsedMilliseconds = now - messageDate;

        const minutes = Math.floor(elapsedMilliseconds / 1000 / 60);
        const hours = Math.floor(minutes / 60);
        const days = Math.floor(hours / 24);
        const months = Math.floor(days / 30);
        

        if (minutes < 1) {
            return "Justo ahora";
        } else if (minutes < 60) {
            return `Hace ${minutes}m`;
        } else if (hours < 24) {
            return `Hace ${hours}h`;
        } else if (days === 1) {
            return "Hace 1 día";
        } else if (days < 30) {
            return `Hace ${days} días`;
        } else if (months === 1) {
            return "Hace 1 mes";
        } else {
            return `Hace ${months} meses`;
        }
    }
    const chatsContainer = document.getElementById('chatsContainer')
    let icon = [`<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2 mx-1" viewBox="0 0 16 16">
    <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0"/>
    </svg>`, `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-all mx-1" viewBox="0 0 16 16">
    <path d="M8.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L2.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093L8.95 4.992zm-.92 5.14.92.92a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 1 0-1.091-1.028L9.477 9.417l-.485-.486z"/>
    </svg>`, `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#176b87" class="bi bi-check-all mx-1" viewBox="0 0 16 16">
    <path d="M8.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L2.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093L8.95 4.992zm-.92 5.14.92.92a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 1 0-1.091-1.028L9.477 9.417l-.485-.486z"/>
    </svg>`];
    fetch('/api/get/chats/chats.php')
    .then((res)=>{return res.json()})
    .then((res)=>{
        if (res.status) {
            chatsContainer.innerHTML = '';
            res.data.forEach(chat => {
                let name = chat.is_group == 1 ? chat.group_name : chat.direct_user_name
                let iconToPrint = '';
                if (chat.unread_messages == 0 && chat.status != null) {
                    iconToPrint = icon[chat.status];

                }
                chatsContainer.innerHTML += `
                <li class="p-2 border-bottom">
        <a href="/views/chats/chat.php?id=${chat.chat_id}" class="d-flex">
        <div class="d-flex flex-row">
            <img
                src="https://via.placeholder.com/50"
                alt="avatar" class="d-flex align-self-center me-3" width="60">
            <div class="pt-1 msgContent">
                <p class="fw-bold mb-0 text">${name}</p>
                <p class="small text-muted msg">${iconToPrint}${chat.last_message === null ? '': chat.last_message}</p>
            </div>
            <div class="pt-1">
                <p class="small text-muted mb-1">${getTimeElapsed(chat.message_time)}</p>
                ${chat.unread_messages == 0 ? '' : `<span class="badge bg-danger rounded-pill float-end">${chat.unread_messages}</span>`}
            </div>
        </div>
        </a>
    </li>`
            });
        }
        
    })
    .catch(err =>{
        console.log(err);
        
        chatsContainer.innerHTML = `<div class="alert alert-danger m-4" role="alert">
  No se puede acceder a los chats en este momento, vuelva a intentarlo mas tarde.   
    </div>`
    })
    

    const btnViewContac = document.getElementById('btnViewContac')

    document.addEventListener("DOMContentLoaded", () => {
    const myContactsList = document.getElementById("myContactsList");
    const sentRequestsList = document.getElementById("sentRequestsList");
    const receivedRequestsList = document.getElementById("receivedRequestsList");

    async function fetchContacts() {
        return [
            { id: 1, name: "Juan Pérez" },
            { id: 2, name: "María López" },
        ];
    }

    async function fetchSentRequests() {
        return [
            { id: 3, name: "Carlos Sánchez", status: "pending" },
        ];
    }

    async function fetchReceivedRequests() {
        return [
            { id: 4, name: "Ana Rodríguez", status: "pending" },
        ];
    }

    // Función para cargar datos en las listas
    async function loadContactsData() {
        myContactsList.innerHTML = "";
        sentRequestsList.innerHTML = "";
        receivedRequestsList.innerHTML = "";

        // Cargar contactos
        const contacts = await fetchContacts();
        contacts.forEach(contact => {
            const li = document.createElement("li");
            li.className = "list-group-item";
            li.textContent = contact.name;
            myContactsList.appendChild(li);
        });

        // Cargar solicitudes hechas
        const sentRequests = await fetchSentRequests();
        sentRequests.forEach(request => {
            const li = document.createElement("li");
            li.className = "list-group-item";
            li.textContent = `${request.name} (${request.status})`;
            sentRequestsList.appendChild(li);
        });

        // Cargar solicitudes recibidas
        const receivedRequests = await fetchReceivedRequests();
        receivedRequests.forEach(request => {
            const li = document.createElement("li");
            li.className = "list-group-item";

            const nameSpan = document.createElement("span");
            nameSpan.textContent = `${request.name} (${request.status})`;

            const acceptButton = document.createElement("button");
            acceptButton.className = "btn btn-success btn-sm";
            acceptButton.textContent = "Aceptar";
            acceptButton.onclick = () => handleRequestAction(request.id, "accepted");

            const rejectButton = document.createElement("button");
            rejectButton.className = "btn btn-danger btn-sm";
            rejectButton.textContent = "Rechazar";
            rejectButton.onclick = () => handleRequestAction(request.id, "rejected");

            li.appendChild(nameSpan);
            li.appendChild(acceptButton);
            li.appendChild(rejectButton);
            receivedRequestsList.appendChild(li);
        });
    }

    // Función para manejar la acción de aceptar/rechazar solicitudes
    async function handleRequestAction(requestId, action) {
        console.log(`Request ID: ${requestId}, Action: ${action}`);
        // Realiza la llamada a tu API para actualizar la solicitud
        alert(`Solicitud ${action}`);
        // Recargar datos
        await loadContactsData();
    }

   
    
    });

    
    const requestsBTN = document.getElementById("requestsBTN");
    let requestsModal = document.getElementById("contactsModal");
    let myModal = new bootstrap.Modal(requestsModal);
   

    requestsBTN.addEventListener("click", ()=>{
        myModal.show();
        fetch(`/api/get/chats/getRequests.php`)
        .then(response => response.json())
        .then(data => {
            const pendingRequestsTable = document.getElementById('pendingRequestsTable').getElementsByTagName('tbody')[0];
            
            pendingRequestsTable.innerHTML = '';

            if (data.length > 0) {
                data.forEach(request => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="bg-aux text">${request.first_name} ${request.last_name}</td>
                        <td class="bg-aux text">${request.account_number}</td>
                    `;
                    pendingRequestsTable.appendChild(row);
                });
            } else {
                pendingRequestsTable.innerHTML = '<tr><td colspan="2" class="bg-aux text">No tienes solicitudes pendientes.</td></tr>';
            }
        })
        .catch(error => console.error('Error al obtener solicitudes pendientes:', error));


        fetch(`/api/get/chats/getContacts.php`)
        .then(response => response.json())
        .then(data => {
            const contactsTable = document.getElementById('contacts').getElementsByTagName('tbody')[0];

            // Limpiar la tabla de contactos antes de llenarla
            contactsTable.innerHTML = '';

            if (data.length > 0) {
                data.forEach(contact => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="bg-aux text">${contact.first_name} ${contact.last_name}</td>
                        <td class="bg-aux text">${contact.email}</td>
                        <td class="bg-aux text">${contact.phone_number}</td>
                    `;
                    contactsTable.appendChild(row);
                });
            } else {
                // Si no hay contactos, mostrar mensaje
                contactsTable.innerHTML = '<tr><td colspan="3" class="bg-aux text">No tienes contactos.</td></tr>';
            }
        })
        .catch(error => console.error('Error al obtener los contactos:', error));

       


        fetch(`/api/get/chats/requestsReceived.php`)
        .then(response => response.json())
        .then(data => {
            const receivedRequestsTable = document.getElementById('receivedRequestsTable').getElementsByTagName('tbody')[0];

            receivedRequestsTable.innerHTML = '';

            if (data.length > 0) {
                data.forEach(request => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="bg-aux text">${request.first_name} ${request.last_name}</td>
                        <td class="bg-aux text">${request.personal_email}</td>
                        <td class="bg-aux text">${request.phone}</td>
                        <td class="bg-aux text">
                            <button class="btn btn-success btn-sm m-1" onclick="updateRequestStatus(${request.request_id}, 'accepted')">Aceptar</button>
                            <button class="btn btn-danger btn-sm m-1" onclick="updateRequestStatus(${request.request_id}, 'rejected')">Rechazar</button>
                        </td>
                    `;
                    receivedRequestsTable.appendChild(row);
                });
            } else {
                receivedRequestsTable.innerHTML = '<tr><td colspan="4" class="bg-aux text">No tienes solicitudes recibidas.</td></tr>';
            }
            })
        .catch(error => console.error('Error al obtener los contactos:', error));

})

function updateRequestStatus(request_id, status) {
            fetch('/api/put/chat/updateRequestStatus.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `request_id=${request_id}&status=${status}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    
                    alert(data.message);

                    receivedRequestsBTN.click(); 
                } else {
                    alert(data.error || 'Hubo un error al actualizar la solicitud.');
                }
            })
            .catch(error => console.error('Error al actualizar la solicitud:', error));
        }
    

</script>
</body>
</html>

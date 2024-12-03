<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversación</title>
    <link rel="stylesheet" href="/public/css/theme.css">
    <link rel="icon" type="image/png" href="/public/images/logo.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="/public/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <style>
     #chat3 .form-control {
border-color: transparent;
}

#chat3 .form-control:focus {
border-color: transparent;
box-shadow: inset 0px 0px 0px 1px transparent;
}

.badge-dot {
border-radius: 50%;
height: 10px;
width: 10px;
margin-left: 2.9rem;
margin-top: -.75rem;
}
.container {
    max-height: 100%;
    overflow: hidden;
}
#chat, #chatsV {
    height: 100%;
    max-height: 100vh !important;
    overflow-y: scroll;
}
.messages {
    display: block;
  flex-grow: 1;
  flex-shrink: 2;
  height: 100vh;
}
.bar, .header-chat {
    display: block;
  flex-grow: 0;
  flex-shrink: 1;

}
.bar {
    border-top: 1px solid #ddd;
}

.square {
    aspect-ratio: 1/1 !important;
    width: 40px;
    height: 40px;
    display: flex;
    justify-content: center;
    align-items: center;
}
.user1 {
    background-color: var(--primary-color);
}
.header-chat {
            display: flex;
            justify-content: start;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding: 5px 0;
        }
a {
    text-decoration: none;
}
        .header-chat .btn-back {
            font-size: 14px;
        }

        img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }
        .text-muted {
            color: var( --secondary-color) !important;
        }
*::-webkit-scrollbar {
    display: none;
}
.messages::-webkit-scrollbar, #chatsV::-webkit-scrollbar {
  width: 7px;
  display: block !important;
}
 
.messages::-webkit-scrollbar-track, #chatsV::-webkit-scrollbar-track {
  background: var(--bg);
}
 
.messages::-webkit-scrollbar-thumb, #chatsV::-webkit-scrollbar-thumb {
  background: var(--primary-color);
  border-radius: 1rem;
}
.msg {
    max-width: 80%;
}
    </style>
</head>
<body>

    <div class="container-fluid" id="container">
  
      <div class="row h-full">
        <div class="col-md-12 h-full">
  
          <div class="h-full" id="chat3" style="border-radius: 15px;">
            <div class="h-full">
  
              <div class="row p-0 h-full">
                <div class="col-md-6 chatsV d-none d-md-block col-lg-5 col-xl-4 p-0 mb-4 mb-md-0">
  
                  <div class="p-0">
  
                      
                      <div id="chatsV" style="position: relative">
                        <div class="input-group rounded mb-3 p-3">
                          <input type="Buscar" class="form-control rounded" placeholder="Search" aria-label="Search"
                            aria-describedby="search-addon" />
                          <span class="input-group-text text bg-aux border-0" id="search-addon">
                          <i class="bi bi-search"></i>
                          </span>
                        </div>
                      <ul class="list-unstyled mb-0">
                        <li class="p-2 border-bottom">
                          <a href="#!" class="d-flex justify-content-between">
                            <div class="d-flex flex-row">
                              <div>
                                <img
                                  src="https://via.placeholder.com/50"
                                  alt="avatar" class="d-flex align-self-center me-3" width="60">
                                <span class="badge bg-success badge-dot"></span>
                              </div>
                              <div class="pt-1">
                                <p class="fw-bold mb-0">Marie Horwitz</p>
                                <p class="small text-muted">Hello, Are you there?</p>
                              </div>
                            </div>
                            <div class="pt-1">
                              <p class="small text-muted mb-1">Just now</p>
                              <span class="badge bg-danger rounded-pill float-end">3</span>
                            </div>
                          </a>
                        </li>
                        <li class="p-2 border-bottom">
                          <a href="#!" class="d-flex justify-content-between">
                            <div class="d-flex flex-row">
                              <div>
                                <img
                                  src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava2-bg.webp"
                                  alt="avatar" class="d-flex align-self-center me-3" width="60">
                                <span class="badge bg-warning badge-dot"></span>
                              </div>
                              <div class="pt-1">
                                <p class="fw-bold mb-0">Alexa Chung</p>
                                <p class="small text-muted">Lorem ipsum dolor sit.</p>
                              </div>
                            </div>
                            <div class="pt-1">
                              <p class="small text-muted mb-1">5 mins ago</p>
                              <span class="badge bg-danger rounded-pill float-end">2</span>
                            </div>
                          </a>
                        </li>
                        <li class="p-2 border-bottom">
                          <a href="#!" class="d-flex justify-content-between">
                            <div class="d-flex flex-row">
                              <div>
                                <img
                                  src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3-bg.webp"
                                  alt="avatar" class="d-flex align-self-center me-3" width="60">
                                <span class="badge bg-success badge-dot"></span>
                              </div>
                              <div class="pt-1">
                                <p class="fw-bold mb-0">Danny McChain</p>
                                <p class="small text-muted">Lorem ipsum dolor sit.</p>
                              </div>
                            </div>
                            <div class="pt-1">
                              <p class="small text-muted mb-1">Yesterday</p>
                            </div>
                          </a>
                        </li>
                        <li class="p-2 border-bottom">
                          <a href="#!" class="d-flex justify-content-between">
                            <div class="d-flex flex-row">
                              <div>
                                <img
                                  src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava4-bg.webp"
                                  alt="avatar" class="d-flex align-self-center me-3" width="60">
                                <span class="badge bg-danger badge-dot"></span>
                              </div>
                              <div class="pt-1">
                                <p class="fw-bold mb-0">Ashley Olsen</p>
                                <p class="small text-muted">Lorem ipsum dolor sit.</p>
                              </div>
                            </div>
                            <div class="pt-1">
                              <p class="small text-muted mb-1">Yesterday</p>
                            </div>
                          </a>
                        </li>
                        <li class="p-2 border-bottom">
                          <a href="#!" class="d-flex justify-content-between">
                            <div class="d-flex flex-row">
                              <div>
                                <img
                                  src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava5-bg.webp"
                                  alt="avatar" class="d-flex align-self-center me-3" width="60">
                                <span class="badge bg-warning badge-dot"></span>
                              </div>
                              <div class="pt-1">
                                <p class="fw-bold mb-0">Kate Moss</p>
                                <p class="small text-muted">Lorem ipsum dolor sit.</p>
                              </div>
                            </div>
                            <div class="pt-1">
                              <p class="small text-muted mb-1">Yesterday</p>
                            </div>
                          </a>
                        </li>
                        <li class="p-2 border-bottom">
                          <a href="#!" class="d-flex justify-content-between">
                            <div class="d-flex flex-row">
                              <div>
                                <img
                                  src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava5-bg.webp"
                                  alt="avatar" class="d-flex align-self-center me-3" width="60">
                                <span class="badge bg-warning badge-dot"></span>
                              </div>
                              <div class="pt-1">
                                <p class="fw-bold mb-0">Kate Moss</p>
                                <p class="small text-muted">Lorem ipsum dolor sit.</p>
                              </div>
                            </div>
                            <div class="pt-1">
                              <p class="small text-muted mb-1">Yesterday</p>
                            </div>
                          </a>
                        </li>
                        <li class="p-2 border-bottom">
                          <a href="#!" class="d-flex justify-content-between">
                            <div class="d-flex flex-row">
                              <div>
                                <img
                                  src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava5-bg.webp"
                                  alt="avatar" class="d-flex align-self-center me-3" width="60">
                                <span class="badge bg-warning badge-dot"></span>
                              </div>
                              <div class="pt-1">
                                <p class="fw-bold mb-0">Kate Moss</p>
                                <p class="small text-muted">Lorem ipsum dolor sit.</p>
                              </div>
                            </div>
                            <div class="pt-1">
                              <p class="small text-muted mb-1">Yesterday</p>
                            </div>
                          </a>
                        </li>
                        <li class="p-2 border-bottom">
                          <a href="#!" class="d-flex justify-content-between">
                            <div class="d-flex flex-row">
                              <div>
                                <img
                                  src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava5-bg.webp"
                                  alt="avatar" class="d-flex align-self-center me-3" width="60">
                                <span class="badge bg-warning badge-dot"></span>
                              </div>
                              <div class="pt-1">
                                <p class="fw-bold mb-0">Kate Moss</p>
                                <p class="small text-muted">Lorem ipsum dolor sit.</p>
                              </div>
                            </div>
                            <div class="pt-1">
                              <p class="small text-muted mb-1">Yesterday</p>
                            </div>
                          </a>
                        </li>
                        <li class="p-2 border-bottom">
                          <a href="#!" class="d-flex justify-content-between">
                            <div class="d-flex flex-row">
                              <div>
                                <img
                                  src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava5-bg.webp"
                                  alt="avatar" class="d-flex align-self-center me-3" width="60">
                                <span class="badge bg-warning badge-dot"></span>
                              </div>
                              <div class="pt-1">
                                <p class="fw-bold mb-0">Kate Moss</p>
                                <p class="small text-muted">Lorem ipsum dolor sit.</p>
                              </div>
                            </div>
                            <div class="pt-1">
                              <p class="small text-muted mb-1">Yesterday</p>
                            </div>
                          </a>
                        </li>
                        <li class="p-2 border-bottom">
                          <a href="#!" class="d-flex justify-content-between">
                            <div class="d-flex flex-row">
                              <div>
                                <img
                                  src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava5-bg.webp"
                                  alt="avatar" class="d-flex align-self-center me-3" width="60">
                                <span class="badge bg-warning badge-dot"></span>
                              </div>
                              <div class="pt-1">
                                <p class="fw-bold mb-0">Kate Moss</p>
                                <p class="small text-muted">Lorem ipsum dolor sit.</p>
                              </div>
                            </div>
                            <div class="pt-1">
                              <p class="small text-muted mb-1">Yesterday</p>
                            </div>
                          </a>
                        </li>
                        <li class="p-2">
                          <a href="#!" class="d-flex justify-content-between">
                            <div class="d-flex flex-row">
                              <div>
                                <img
                                  src="https://via.placeholder.com/50"
                                  alt="avatar" class="d-flex align-self-center me-3" width="60">
                                <span class="badge bg-success badge-dot"></span>
                              </div>
                              <div class="pt-1">
                                <p class="fw-bold mb-0">Ben Smith</p>
                                <p class="small text-muted">Lorem ipsum dolor sit.</p>
                              </div>
                            </div>
                            <div class="pt-1">
                              <p class="small text-muted mb-1">Yesterday</p>
                            </div>
                          </a>
                        </li>
                      </ul>
                    </div>
  
                  </div>
  
                </div>
  
                <div class="col-md-6 col-12 col-lg-7 col-xl-8 flex flex-col h-full p-0" id="chat">
                <div class="header-chat">
                    <a href="/views/chats/index.php" class="btn btn-back">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0"/>
                        </svg>
                        <img src="https://via.placeholder.com/40" alt="Usuario 1" class="me-3">
                    </a>
                    <div class="d-flex align-items-center" style="width: calc(100% - 120px);">
                        <div class="w-full">
                            <div class="w-full"><strong class="w-full" id="chatName"><p class=" w-full placeholder-glow">
                          <span class="placeholder col-12  bg-dark"></span>
                      </p></strong></div>
                            <small id="lastCon" class="text-muted text-sm"><p class=" w-full placeholder-glow">
                          <span class="placeholder col-6  bg-dark"></span>
                      </p></small>
                        </div>
                    </div>
                </div>

                  <div class="messages p-2" data-mdb-perfect-scrollbar-init
                    style=" overflow-y:scroll" id="messagesContainer">
  
                    <div class="d-flex flex-row justify-content-start">
                      <div class="msg w-full">
                        <p class=" w-full placeholder-glow">
                          <span class="placeholder col-12  bg-dark"></span>
                      </p>
                      </div>
                    </div>
  
                    <div class="d-flex flex-row justify-content-end">
                    <div class="msg w-full">
                        <p class=" w-full placeholder-glow">
                          <span class="placeholder col-12  bg-dark"></span>
                      </p>
                      </div>
                    </div>
                    
                    <div class="d-flex flex-row justify-content-start">
                      <div class="msg w-full">
                        <p class=" w-full placeholder-glow">
                          <span class="placeholder col-12  bg-dark"></span>
                      </p>
                      </div>
                    </div>

                  </div>
  
                  <div class="text-muted">
                    <div class=" ">
                      <div class="row" id="fileNameOut">
                      </div>
                      <div class="row">
                        <div class="d-flex justify-content-start align-items-center bar">

                          <input type="text" class="form-control form-control-lg m-1" id="exampleFormControlInput2"
                            placeholder="Escribe un mensaje">
                          <input type="file" name="" class="d-none" id="file">
                          <label for="file">
                              <i class="mx-1 text-muted bi bi-paperclip"></i>
                          </label>
                          <a id="sendBtn" class="mx-3 ratio1/1 rounded-full square p-2 bg-custom-primary" href="#!"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#fff" class="bi bi-send" viewBox="0 0 16 16">
                              <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576zm6.787-8.201L1.591 6.602l4.339 2.76z"/>
                              </svg></a>
                        </div>
                      </div>
                    </div>
                  </div>
  
                </div>
              </div>
  
            </div>
          </div>
  
        </div>
      </div>
  
    </div>


    <script src="/public/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
<script>
    let params = new URLSearchParams(document.location.search);
    const container = document.getElementById('container');
    const chatName = document.getElementById('chatName');
    const fileNameOut = document.getElementById('fileNameOut');
    const lastCon = document.getElementById('lastCon');
    const messagesContainer = document.getElementById('messagesContainer');
    let id = params.get("id");
    let icon = [`<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2 mx-1" viewBox="0 0 16 16">
          <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0"/>
        </svg>`, `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-all mx-1" viewBox="0 0 16 16">
          <path d="M8.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L2.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093L8.95 4.992zm-.92 5.14.92.92a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 1 0-1.091-1.028L9.477 9.417l-.485-.486z"/>
        </svg>`, `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#176b87" class="bi bi-check-all mx-1" viewBox="0 0 16 16">
          <path d="M8.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L2.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093L8.95 4.992zm-.92 5.14.92.92a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 1 0-1.091-1.028L9.477 9.417l-.485-.486z"/>
        </svg>`];

    fetch('/api/get/chats/chat.php?id='+id)
    .then((res)=>{return res.json()})
    .then(res=>{
      if (!res.status) throw new Error(res.message);
      messagesContainer.innerHTML = '';
      chatName.innerHTML = res.info.chat_name;
      lastCon.innerHTML = res.info.last_connection == null ? '' : res.info.last_connection;
      res.data.forEach(msg => {
            
            let iconToPrint = '';
            if (msg.sender_type == "me" && msg.status != null) {
                iconToPrint = icon[msg.status];
            }
            const sentAt = new Date(msg.sent_at_adjusted);
            const formattedTime = sentAt.toLocaleString('en-US', {
                hour: 'numeric',
                minute: '2-digit',
                hour12: false,
            });
            const formattedDate = sentAt.toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
            });

            let fileHTML = '';
            if (msg.fileContent && msg.file_extension) {
                const fileURL = `data:application/octet-stream;base64,${msg.fileContent}`;
                fileHTML = `
                    <hr>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#fff" class="bi bi-file-earmark-arrow-down mr-1" viewBox="0 0 16 16">
                        <path d="M8.5 6.5a.5.5 0 0 0-1 0v3.793L6.354 9.146a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 10.293z"/>
                        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 1 1-1h5.5z"/>
                    </svg>
                    <a href="${fileURL}" download="${msg.file_name}" class="text-muted ml-2">${msg.file_name}</a>
                `;
            }

            const messageHTML = msg.sender_type == "me"
            ? `
            <div class="d-flex flex-row justify-content-end">
                <div class="msg">
                   <div class="user1 small p-2 me-3 mb-1 text-white rounded-3">
                        <p class="mb-1">${msg.content}</p>
                        <div class="text-muted">${fileHTML}</div>
                    </div>
                    <div class="flex  me-3 mb-3  justify-between items-center">
                      <p class="small mb-0 rounded-3 text-muted">${formattedTime} | ${formattedDate} </p>
                        <div class="icon mx-1">
          ${iconToPrint}
                        </div>
                    </div>
                </div>
            </div>
            `
            : `
            <div class="d-flex flex-row justify-content-start">
                <div class="msg">
                    <div class="bg-aux text small p-2 me-3 mb-1 rounded-3">
                        <p class="mb-1">${msg.content}</p>
                        <div class="text-muted">${fileHTML}</div>
                    </div>
                    <p class="small mb-3 rounded-3 text-muted">${formattedTime} | ${formattedDate}</p>
                </div>
            </div>
            `;

        messagesContainer.innerHTML += messageHTML;
      });
      messagesContainer.innerHTML += `<div id="lastMsg"></div>`;

document.getElementById('lastMsg').scrollIntoView({ behavior: 'smooth', block: 'end' });
    })
    .catch(err=>{
      console.log(err);
      
      container.innerHTML = `<div class="alert alert-danger mt-4" role="alert">
    ${err}
</div>`;
    })
    
    const sendBtn = document.getElementById('sendBtn');
    const exampleFormControlInput2 = document.getElementById('exampleFormControlInput2');
    const fileInput = document.getElementById('file');
    let messageId = 0;

    file.addEventListener('change', (e)=>{
      if (file) {
        fileNameOut.innerHTML = fileInput.files[0].name
      } else {
        fileNameOut.innerHTML = ''
    }
    })

    sendBtn.addEventListener('click', ()=>{
        const messageText = exampleFormControlInput2.value.trim();
        const file = fileInput.files[0];
        if (!messageText && !file) {
            return;
        }
        const currentMessageId = ++messageId;
        const sentAt = new Date();
            const formattedTime = sentAt.toLocaleString('en-US', {
                hour: 'numeric',
                minute: '2-digit',
                hour12: false,
            });
            const formattedDate = sentAt.toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
            });
        const formData = new FormData();
        formData.append("message", messageText)
        formData.append("chatID", id)
        let fileHTML = '';
        if (file) {
          formData.append("file", file);
          const fileURL = URL.createObjectURL(file);
          fileHTML = `<hr><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#fff" class="bi bi-file-earmark-arrow-down mr-1" viewBox="0 0 16 16">
              <path d="M8.5 6.5a.5.5 0 0 0-1 0v3.793L6.354 9.146a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 10.293z"/>
              <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z"/>
            </svg><a href="${fileURL}" download="${file.name}" class="text-muted ml-2">${file.name}</a>`;
        }
        messagesContainer.innerHTML += `<div class="d-flex flex-row justify-content-end " id="message-${currentMessageId}">
                        <div class="msg">
                        <div class="user1 small p-2 me-3 mb-1 text-white rounded-3">
                            <p class="mb-1">${messageText}</p>
                              <div class="text-muted">${fileHTML}</div>
                            
                            </div>
                            <div class="flex  me-3 mb-3  justify-between items-center">
                              <p class="small mb-0 rounded-3 text-muted">${formattedTime} | ${formattedDate}</p>
                                <div class="icon mx-3">
                                    <i class="bi bi-clock"></i>
                                </div>
                            </div>
                        </div>
                    </div>
        `;
        location.href = `#message-${currentMessageId}`

        fileNameOut.innerHTML = '';
        exampleFormControlInput2.value = '';
        fileInput.value = '';
      fetch('/api/post/chats/send.php', {
        method: 'POST',
        body: formData
      })
      .then(response => {
        if (!response.ok) throw new Error('Failed to send message');
        return response.json();
      })
      .then(() => {
        const statusElement = document.querySelector(`#message-${currentMessageId} .msg div .icon`);
        if (statusElement) {
          statusElement.innerHTML = '<i class="bi bi-check2 text-muted"></i>';
        }
        
      })
      .catch(error => {
        console.error(error);
        const statusElement = document.querySelector(`#message-${currentMessageId} .msg div .icon`);
        if (statusElement) {
          statusElement.innerHTML = '<i class="bi bi-x-lg  text-danger"></i>';
        }
      });


    })
    
</script>
</body>
</html>

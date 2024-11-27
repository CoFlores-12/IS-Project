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

    <div class="container-fluid">
  
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
                    <div class="d-flex align-items-center">
                        <div>
                            <div><strong>Usuario 1</strong></div>
                            <small class="text-muted text-sm">Última vez activo: 2 horas</small>
                        </div>
                    </div>
                </div>

                  <div class="messages p-2" data-mdb-perfect-scrollbar-init
                    style=" overflow-y:scroll" id="messagesContainer">
  
                    <div class="d-flex flex-row justify-content-start">
                      <div class="msg">
                        <p class="small p-2 ms-3 mb-1 rounded-3 bg-aux">Lorem ipsum
                          dolor</p>
                        <p class="small ms-3 mb-3 rounded-3 text-muted float-end">12:00 PM | Aug 13</p>
                      </div>
                    </div>
  
                    <div class="d-flex flex-row justify-content-end">
                      <div class="msg">
                        <p class="small p-2 me-3 mb-1 text-white rounded-3 user1">Ut enim ad minim veniam,
                          quis
                          nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                        <p class="small me-3 mb-3 rounded-3 text-muted">12:00 PM | Aug 13</p>
                      </div>
                    </div>
  
                    <div class="d-flex flex-row justify-content-start">
                      <div class="msg">
                        <p class="small p-2 ms-3 mb-1 rounded-3 bg-aux">Duis aute
                          irure
                          dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                        </p>
                        <p class="small ms-3 mb-3 rounded-3 text-muted float-end">12:00 PM | Aug 13</p>
                      </div>
                    </div>
  
                    <div class="d-flex flex-row justify-content-end">
                      <div class="msg">
                        <p class="small p-2 me-3 mb-1 text-white rounded-3 user1">Excepteur sint occaecat
                          cupidatat
                          non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                        <p class="small me-3 mb-3 rounded-3 text-muted">12:00 PM | Aug 13</p>
                      </div>
                      
                    </div>
  
                    <div class="d-flex flex-row justify-content-start">
                      <div class="msg">
                        <p class="small p-2 ms-3 mb-1 rounded-3 bg-aux">Sed ut
                          perspiciatis
                          unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam
                          rem
                          aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae
                          dicta
                          sunt explicabo.</p>
                        <p class="small ms-3 mb-3 rounded-3 text-muted float-end">12:00 PM | Aug 13</p>
                      </div>
                    </div>
  
                    <div class="d-flex flex-row justify-content-end">
                      <div class="msg">
                        <p class="small p-2 me-3 mb-1 text-white rounded-3 user1">Nemo enim ipsam
                          voluptatem quia
                          voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos
                          qui
                          ratione voluptatem sequi nesciunt.</p>
                        <p class="small me-3 mb-3 rounded-3 text-muted">12:00 PM | Aug 13</p>
                      </div>
                    </div>
  
                    <div class="d-flex flex-row justify-content-start">
                      <div class="msg">
                        <p class="small p-2 ms-3 mb-1 rounded-3 bg-aux">Neque porro
                          quisquam
                          est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non
                          numquam
                          eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.</p>
                        <p class="small ms-3 mb-3 rounded-3 text-muted float-end">12:00 PM | Aug 13</p>
                      </div>
                    </div>
  
                    <div class="d-flex flex-row justify-content-end">
                      <div class="msg">
                        <p class="small p-2 me-3 mb-1 text-white rounded-3 user1">Ut enim ad minima veniam,
                          quis
                          nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea
                          commodi
                          consequatur?</p>
                          <div class="flex  me-3 mb-3  justify-between items-center">
                              <p class="small mb-0 rounded-3 text-muted">12:00 PM | Aug 13</p>
                                <div class="icon mx-3">
                                    <i class="bi bi-check2"></i>
                                </div>
                          </div>
                      </div>
                    </div>
  
                  </div>
  
                  <div class="text-muted bar d-flex justify-content-start align-items-center">
                    
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


    <script src="/public/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
<script>
    let params = new URLSearchParams(document.location.search);
    let id = params.get("id");

    //TODO get messages
    
    const sendBtn = document.getElementById('sendBtn');
    const messagesContainer = document.getElementById('messagesContainer');
    const exampleFormControlInput2 = document.getElementById('exampleFormControlInput2');
    const file = document.getElementById('file');
    let messageId = 0;

    sendBtn.addEventListener('click', ()=>{
        const messageText = exampleFormControlInput2.value.trim();
        if (!messageText) {
            return;
        }
        const currentMessageId = ++messageId;
        messagesContainer.innerHTML += `<div class="d-flex flex-row justify-content-end " id="message-${currentMessageId}">
                        <div class="msg">
                            <p class="small p-2 me-3 mb-1 text-white rounded-3 user1">${messageText}</p>
                            <div class="flex  me-3 mb-3  justify-between items-center">
                              <p class="small mb-0 rounded-3 text-muted">12:00 PM | Aug 13</p>
                                <div class="icon mx-3">
                                    <i class="bi bi-clock"></i>
                                </div>
                            </div>
                        </div>
                    </div>
        `;
        location.href = `#message-${currentMessageId}`


        const formData = new FormData();
        formData.append("message", messageText)
        formData.append("chatID", id)
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

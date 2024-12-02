let btnModalRequests = document.getElementById('btnModalRequests');
let btnModalEnrollment = document.getElementById('btnModalEnrollment');
let cancelEnrollmentBtn = document.getElementById('cancelEnrollmentBtn');
let addEnrollmentBtn = document.getElementById('addEnrollmentBtn');
let modalRequests = document.getElementById('modalRequests');
let MyRequestModal = document.getElementById('MyRequestModal');
let modalEnrollment = document.getElementById('modalEnrolment');
let requestType = document.getElementById('requestType');
let dataForRequest = document.getElementById('dataForRequest');
let formDataEnrollment = document.getElementById('form-data');
let enrollBtn = document.getElementById('enrollBtn');
let cancelBtn = document.getElementById('cancelBtn');
let toast = document.getElementById('toast');
let toastBody = document.getElementById('toastBody');
let toastTitle = document.getElementById('toastTitle');
let toastBS = new bootstrap.Toast(toast);
let refreshChats = document.getElementById('refreshChats');
let frameChats = document.getElementById('frameChats');
let modalRequestsBS = new bootstrap.Modal(modalRequests);
let MyRequestModalBS = new bootstrap.Modal(MyRequestModal);
let modalEnrollmentBS = new bootstrap.Modal(modalEnrollment);
const refreshIcon = document.getElementById('refreshIcon');
const courseHistory = document.getElementById('courseHistory');
const btnModalMyRequests = document.getElementById('btnModalMyRequests');

refreshChats.addEventListener('click', () => {
    refreshChats.classList.add('rotate');

    frameChats.contentWindow.location.reload();

    setTimeout(() => {
        refreshChats.classList.remove('rotate');
    }, 1000); 
});

let optionsBody = async (value) => {
    let body = `<textarea name="comments" placeholder="Justificación" id="comments" class="form-control bg-aux my-4 text"></textarea>`;
    switch (value) {
        case "2": 
        try {
            const response = await fetch('/api/get/students/exceptionalCancellation.php', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            });
            const data = await response.json();
        
            if (data.status === true) {
                const response1 = await fetch(`/api/get/students/getClassesRunning.php`);
                const data1 = await response1.json();

                body += `
                    <input type="file" name="evidemce" accept="application/pdf" class="form-control my-4" id="evidence">
                `;

                body += `<h5 class="modal-title text pb-2" >Seleccione las clases a cancelar</h5>`;
        
                body += "<div id='checkboxGroup'>";

                data1.forEach((clase) => {
                    console.log(clase);
                    
                    if (clase.is_waitlist != 1) {
                        body += `
                            <label>
                                <input type="checkbox" name="classToCancel[]" value="${clase.section_id}">
                                ${clase.class_code} ${clase.class_name} (${clase.hour_start})
                            </label><br>
                        `;
                    }
                });
        
                body += "</div>";  
                
                
        
            } else {
                body = `
                    <div class="alert alert-danger mt-3" role="alert">
                        Periodo no activo
                    </div>
                `;
                return body;
            }
        
                
            } catch (error) {
                console.error('Error:', error);
                body = `<div class="alert alert-danger mt-3" role="alert">
                            Error, algo salio mal, vuelva a intentarlo
                        </div>`;
            }
            break
        case "3": 
            let HTML = `<select name="careerChange" class="form-control my-4" id="careerChange">
            <option value="">Seleccionar carrera</option>`
            const response = await fetch('/api/get/public/allCareers.php');
            const data = await response.json();

            data.forEach(career => {
                HTML += `<option value="${career.career_id}">${career.career_name}</option>`;
            });
            HTML+= `</select>`;
            body += HTML;
            break;
        case "4":
            let HTML2 = `<select name="careerChange" class="form-control my-4" id="campusChange">
            <option value="">Seleccionar centro regional</option>`
            const response2 = await fetch('/api/get/public/allCampus.php');
            const data2 = await response2.json();

            data2.forEach(campus => {
                HTML2 += `<option value="${campus.center_id}">${campus.center_name}</option>`;
            });
            HTML2+= `</select>`;
            body += HTML2;
            break;
        default:
            break;

    }
    body += `<button id="sendRequestBtn" class="btn bg-custom-primary text-white mt-2 form-control">Enviar</button>`;
    
    return body
    
}

btnModalRequests.addEventListener('click', (e)=>{
    modalRequestsBS.show();
})

btnModalMyRequests.addEventListener('click', (e)=>{
    MyRequestModalBS.show();
    const tbody = document.getElementById('RequestTableBody');
    tbody.innerHTML = '<center><div class="spinner-border text m-4" role="status"></div></center>';
    
    fetch('/api/get/students/myRequsts.php')
    .then(res => {return res.json()})
    .then(res=>{
        tbody.innerHTML = '';
        res.forEach(log => {
          const row = document.createElement('tr');
          if (log.status === 1) {
            row.classList.add('table-success');
          } else if (log.status === 0) {
            row.classList.add('table-danger'); 
        } else {
              row.classList.add('bg-aux'); 

          }
          row.innerHTML = `
            <td>${log.local_time}</td>
            <td>${log.title}</td>
            <td>${log.status === 1 ? 'Aprobada' : log.status === null ? 'Pendiente' : 'Rechazada'}</td>
            <td>${log.response === null ? 'N/A' : log.response}</td>
          `;
          tbody.appendChild(row);
        });
    })
})
btnModalEnrollment.addEventListener('click', (e)=>{
    modalEnrollmentBS.show();
    getClasses();
})

var selected;
var selectedSection;
var selectedCancel;

function highlight(e) {
    if (selected[0]) selected[0].className = '';
    e.target.parentNode.className = 'selected';
    fnselect();
}
function highlightSection(e) {
    if (selectedSection[0]) selectedSection[0].className = '';
    e.target.parentNode.className = 'selected';
    var element = document.querySelectorAll('.selected');
    if(element[0]!== undefined){ 
        enrollBtn.disabled = false
    }else{
        enrollBtn.disabled = true
    }
}
function highlightCancell(e) {
    if (selectedCancel[0]) selectedCancel[0].className = '';
    e.target.parentNode.className = 'selected';
    var element = document.querySelectorAll('.selected');
    if(element[0]!== undefined){ 
        cancelBtn.disabled = false
    }else{
        cancelBtn.disabled = true
    }
}

function fnselect(){
    var element = document.querySelectorAll('.selected');
    enrollBtn.disabled = true
    let tableSections = document.getElementById('tableSections');
    if(element[0]!== undefined){ 
        tableSections.innerHTML = '<center><div class="spinner-border text m-4" role="status"></div></center>';
    
        fetch('/api/get/students/getSections.php?class_id='+element[0].getAttribute('data-class-id'))
        .then((res) => {return res.json()})
        .then((res) =>{
            if (!res.status) {
                toastTitle.innerHTML ='Enroll Error'
                toastBody.innerHTML = `<div class="alert alert-danger mb-0" role="alert">
                    ${res.message}
                </div>`
                toastBS.show();
                tableSections.innerHTML = '';
                return;
            }

            var html = `<tr disabled>
                        <td>Sección</td>
                        <td>Cupos</td>
                        <td>Dias</td>
                        <td>Docente</td>`;
            res.sections.forEach(element => {
                html += `<tr data-section-id="${element.section_id}">
                        <td>${element.hour_start}</td>
                        <td>${element.quotas}</td>
                        <td>${element.days}</td>
                        <td>${element.first_name} ${element.last_name}</td>
                    </tr>`;
            });
            tableSections.innerHTML= html;
            location.href = '#tableSections';
            selectedSection = tableSections.getElementsByClassName('selected');
            tableSections.onclick = highlightSection;
            
        })
    }
}

function getClasses() {
    formDataEnrollment.innerHTML = '<center><div class="spinner-border text m-4" role="status"></div></center>';
    
    fetch('/api/get/students/getClasses.php')
    .then((res) => {return res.json()})
    .then((res) =>{
        if (!res.status) throw new Error(res.message);
        var html = `<table id="table" class="mt-4">`;
        res.data.forEach(element => {
            html += `<tr data-class-id="${element.class_id}">
                    <td>${element.class_code}</td>
                    <td>${element.class_name}</td>
                    <td>${element.uv}</td>
                </tr>`;
        });
        html += `</table><hr><table id="tableSections"></table>`;
        formDataEnrollment.innerHTML= html;
        var table = document.getElementById('table');
        selected = table.getElementsByClassName('selected');
        table.onclick = highlight;
    })
    .catch(err=>{
        formDataEnrollment.innerHTML= `<div class="alert alert-danger mt-3" role="alert">
  ${err}
</div>`;
    })
}

cancelEnrollmentBtn.addEventListener('click', (e)=>{
    e.target.classList.add('active');
    e.target.classList.add('bg-aux');
    enrollBtn.disabled = true;
    enrollBtn.classList.add('d-none');
    cancelBtn.classList.remove('d-none');
    cancelBtn.disabled = true;
    addEnrollmentBtn.classList.remove('active');
    addEnrollmentBtn.classList.remove('bg-aux');
    formDataEnrollment.innerHTML = '<center><div class="spinner-border text m-4" role="status"></div></center>';
    fetch('/api/get/students/getClassesEnrolled.php')
    .then((res)=>{return res.json()})
    .then((res)=> {
        if (!res.status) throw new Error(res.message);
        
        var html = `<table id="table" class="my-2">
        <thead>
            <tr><th colspan="2"><h5>Matriculadas</h5></th></tr>
        </thead>
        <tbody  id="tableEnrolled">
        </tbody>
        <thead>
            <tr><th colspan="2"><h5>Lista de espera</h5></th></tr>
        </thead>
        <tbody id="tableWaitList">
            
        </tbody>
        `;
        formDataEnrollment.innerHTML= html;
        var tableEnrolled = document.getElementById('tableEnrolled');
        var tableWaitList = document.getElementById('tableWaitList');
        res.data.forEach(element => {
            if (element.is_waitlist == 0) {
                tableEnrolled.innerHTML += `<tr data-enrolled-id="${element.enroll_id}">
                <td>${element.hour_start}</td>
                <td>${element.class_name}</td>
                </tr>`;
            } else {
                tableWaitList.innerHTML += `<tr data-enrolled-id="${element.enroll_id}">
                <td>${element.hour_start}</td>
                <td>${element.class_name}</td>
                </tr>`;
            }
        });
        var table = document.getElementById('table');
        selectedCancel = table.getElementsByClassName('selected');
        table.onclick = highlightCancell;
    })
    .catch(err=>{
        formDataEnrollment.innerHTML= `<div class="alert alert-danger mt-3" role="alert">
  ${err}
</div>`;
    })
})
addEnrollmentBtn.addEventListener('click', (e)=>{
    e.target.classList.add('active');
    e.target.classList.add('bg-aux');
    cancelEnrollmentBtn.classList.remove('active');
    cancelEnrollmentBtn.classList.remove('bg-aux');
    cancelBtn.disabled = true;
    cancelBtn.classList.add('d-none');
    enrollBtn.classList.remove('d-none');
    enrollBtn.disabled = true;
    getClasses();
})

requestType.addEventListener('change', async (e)=>{
    const value = e.target.value;
    dataForRequest.innerHTML = `<center><div class="spinner-grow text-secondary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>`
    const newHTML = await optionsBody(value);
    dataForRequest.innerHTML = newHTML;
    addEvents(value);
})
function addEvents(key) {
    const sendRequestBtn = document.getElementById('sendRequestBtn');
    sendRequestBtn.disabled =false;
    sendRequestBtn.addEventListener('click', (e)=>{
        e.target.innerHTML = `<div class="spinner-border text-light" role="status"></div>`
        e.target.disabled = true;
        const formData = new FormData();
        formData.append('request_type_id', document.getElementById('requestType').value);
        formData.append('comments', document.getElementById('comments').value);
        console.log(document.getElementById('checkboxGroup'));
        const checkboxes = document.querySelectorAll('input[name="classToCancel[]"]:checked');
        const selectedValues = Array.from(checkboxes).map(checkbox => checkbox.value);
        const jsonResult = JSON.stringify(selectedValues);
        
        
        try {
            formData.append("evidence", document.getElementById('evidence').files[0])
            formData.append("sections", jsonResult)
        } catch (error) {}
        try {
            formData.append("career_change_id", document.getElementById('careerChange').value)
        } catch (error) {}
        try {
            formData.append("campus_change_id", document.getElementById('campusChange').value)
        } catch (error) {}
        fetch('/api/post/students/createRequest.php',{
            method: 'POST',
            body: formData
        })
        .then((response)=>{return response.json()})
        .then((response)=>{
            toastTitle.innerHTML ='Solicitud guardada'
            toastBody.innerHTML = `<div class="alert alert-success mb-0" role="alert">
                ${response.message}
            </div>`
            toastBS.show();
            e.target.innerHTML = `Enviar`;
            e.target.disabled = false;
            dataForRequest.innerHTML = '';
            modalRequestsBS.hide();
        })
        .catch((err)=>{
            toastTitle.innerHTML ='Error'
            toastBody.innerHTML = `<div class="alert alert-danger mb-0" role="alert">
                ${err}
            </div>`
            toastBS.show();
            e.target.innerHTML = `Send`;
            e.target.disabled = false;
        })
    });
    switch (key) {
        case "3":
            sendRequestBtn.disabled =true;
            document.getElementById('careerChange').addEventListener('change', async (e)=>{
                sendRequestBtn.disabled = false;
                if (e.target.value === '') {
                    sendRequestBtn.disabled = true;
                }
            })
            break;
            case "4":
            sendRequestBtn.disabled =true;
            document.getElementById('campusChange').addEventListener('change', async (e)=>{
                sendRequestBtn.disabled = false;
                if (e.target.value === '') {
                    sendRequestBtn.disabled = true;
                }
            })
        break;
        default:
            break;
    }
    
    
}

enrollBtn.addEventListener('click', (e)=>{
    e.target.innerHTML = `<div class="spinner-border text-white" role="status"></div>`;
    e.target.disabled = true;
    const formData = new FormData();
    formData.append('section_id', selectedSection[0].getAttribute('data-section-id'));
    fetch('/api/post/students/enrollClass.php', {
        method: 'POST',
        body: formData
    })
    .then((res)=>{return res.json()})
    .then((res)=>{
        e.target.innerHTML = `Enroll`; 
        e.target.disabled = true; 
        if (res.status) {
            toastTitle.innerHTML ='Clase matriculada exitosamente'
            toastBody.innerHTML = `<div class="alert alert-success mb-0" role="alert">
                ${res.message}
            </div>`
            toastBS.show();
            getClassesView();
            tableSections.innerHTML = '';
        }else {
            toastTitle.innerHTML ='Error en matricula'
            toastBody.innerHTML = `<div class="alert alert-danger mb-0" role="alert">
                ${res.message}
            </div>`
            toastBS.show();
            
        }
    })
})
cancelBtn.addEventListener('click', (e)=>{
    e.target.innerHTML = `<div class="spinner-border text-white" role="status"></div>`;
    e.target.disabled = true;
    const formData = new URLSearchParams();
    
    formData.append('enrolled_id', selectedCancel[0].getAttribute('data-enrolled-id'));

    fetch('/api/delete/students/cancelClass.php', {
        method: 'DELETE',
        body: formData
    })
    .then((res)=>{return res.json()})
    .then((res)=>{
        
        e.target.innerHTML = `Cancel Class`; 
        e.target.disabled = true; 
        if (res.status) {
            toastTitle.innerHTML ='Cancel class succes'
            toastBody.innerHTML = `<div class="alert alert-success mb-0" role="alert">
                ${res.message}
            </div>`
            toastBS.show();
            getClassesView();
        }else {
            toastTitle.innerHTML ='Cancel class error'
            toastBody.innerHTML = `<div class="alert alert-danger mb-0" role="alert">
                ${res.message}
            </div>`
            toastBS.show();
        }
        cancelEnrollmentBtn.click();
    })

    
})

const bgLightClasses = [
    "bg-red-50", "bg-red-100", "bg-orange-50", "bg-orange-100",
    "bg-yellow-50", "bg-yellow-100", "bg-green-50", "bg-green-100",
    "bg-blue-50", "bg-blue-100", "bg-gray-50", "bg-gray-100", "bg-gray-200"
];

const bgClasses = [
    "bg-blue", "bg-red", "bg-green", "bg-gray", "bg-orange-500",
    "bg-red-50", "bg-red-100", "bg-red-200", "bg-red-300", "bg-red-400",
    "bg-red-500", "bg-red-600", "bg-red-700", "bg-red-800", "bg-red-900",
    "bg-orange-50", "bg-orange-100", "bg-orange-200", "bg-orange-300", "bg-orange-400",
    "bg-orange-600", "bg-orange-700", "bg-orange-800", "bg-orange-900",
    "bg-yellow-50", "bg-yellow-100", "bg-yellow-200", "bg-yellow-300", "bg-yellow-400",
    "bg-yellow-500", "bg-yellow-600", "bg-yellow-700", "bg-yellow-800", "bg-yellow-900",
    "bg-green-50", "bg-green-100", "bg-green-200", "bg-green-300", "bg-green-400",
    "bg-green-500", "bg-green-600", "bg-green-700", "bg-green-800", "bg-green-900",
    "bg-blue-50", "bg-blue-100", "bg-blue-200", "bg-blue-300", "bg-blue-400",
    "bg-blue-500", "bg-blue-600", "bg-blue-700", "bg-blue-800", "bg-blue-900",
    "bg-gray-50", "bg-gray-100", "bg-gray-200", "bg-gray-300", "bg-gray-400",
    "bg-gray-500", "bg-gray-600", "bg-gray-700", "bg-gray-800", "bg-gray-900"
];

function getRandomBgClass() {
    return bgClasses[Math.floor(Math.random() * bgClasses.length)];
}

function getClassesView() {
    const courseRunning = document.getElementById('courseRunning');
    courseRunning.innerHTML = `<div class="card card-course shadow">
                <div class="p-0 card-bd flex flex-column">
                    <div class="name w-full p-2 bg-secondary text-white mb-1">
                        <p class="card-text placeholder-glow">
                            <span class="placeholder col-4"></span>
                        </p>
                    </div>
                    <div class="infoClass p-3">
                        <p class="card-text placeholder-glow">
                            <span class="placeholder bg-secondary col-12"></span>
                            <span class="placeholder bg-secondary col-4"></span>
                            <span class="placeholder bg-secondary col-6"></span>
                            <span class="placeholder bg-secondary col-8"></span>
                        </p>
                        
                    </div>
                </div>
            </div>`;
    courseHistory.innerHTML = `<div class="card card-course shadow">
                <div class="p-0 card-bd flex flex-column">
                    <div class="name w-full p-2 bg-secondary text-white mb-1">
                        <p class="card-text placeholder-glow">
                            <span class="placeholder col-4"></span>
                        </p>
                    </div>
                    <div class="infoClass p-3">
                        <p class="card-text placeholder-glow">
                            <span class="placeholder bg-secondary col-12"></span>
                            <span class="placeholder bg-secondary col-4"></span>
                            <span class="placeholder bg-secondary col-6"></span>
                            <span class="placeholder bg-secondary col-8"></span>
                        </p>
                        
                    </div>
                </div>
            </div>`;
    fetch('/api/get/students/getClassesRunning.php')
    .then((res)=>{return res.json()})
    .then((res)=> {
        courseRunning.innerHTML = '';
        res.forEach(element => {
            if (element.is_waitlist == 0) {
                const bgClass = getRandomBgClass();
                const textClass = bgLightClasses.includes(bgClass) ? "text-dark" : "text-white";
                courseRunning.innerHTML += `<a class="text-decoration-none" href="/views/class/index.php?section_id=${element.section_id}">
                <div class="card card-course shadow">
                    <div class="p-0 card-bd flex flex-column">
                        <div class="name w-full p-2 ${bgClass}  ${textClass} mb-1">
                            ${element.class_code}
                        </div>
                        <div class="infoClass p-2">
                            <span class=" font-bold text-md mb-2">${element.hour_start} ${element.class_name}</span>
                            <div class="pr mt-3">
                                <div class="progress mt-2">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                                    </div>
                                <div class="text-end"><small class="font-light text-xs">Progress (0%)</small></div>
                            </div>
                        </div>
                    </div>
                </div></a>`;
            }
        });
    })
    
}

function getClassesHistory() {
    fetch('/api/get/students/classesHistory.php')
    .then((res)=>{return res.json()})
    .then((res)=> {
        courseHistory.innerHTML = '';
        res.forEach(element => {
            const bgClass = getRandomBgClass();
                const textClass = bgLightClasses.includes(bgClass) ? "text-dark" : "text-white";
                courseHistory.innerHTML += `<a class="text-decoration-none" href="/views/class/index.php?section_id=${element.section_id}">
                <div class="card card-course shadow">
                    <div class="p-0 card-bd flex flex-column">
                        <div class="name w-full font-bold p-2 ${bgClass}  ${textClass} mb-1">
                            ${element.class_code}
                        </div>
                        <div class="infoClass p-2">
                            <span class=" mb-2"><span class="text-md">${element.hour_start}</span> ${element.class_name}</span>
                            <div class="pr mt-3">
                                <div class="progress mt-2">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                                    </div>
                                <div class="text-end"><small class="font-light text-xs">Progress (0%)</small></div>
                            </div>
                        </div>
                    </div>
                </div></a>`;
        });
    })
}
getClassesView();
getClassesHistory();

const toggleAside = document.getElementById('toggleAside');
const desktopAside = document.getElementById('desktopAside');

function toggleSidebar() {
    if (desktopAside.classList.contains('d-md-block')) {
        desktopAside.classList.remove('d-md-block');
        desktopAside.classList.add('d-md-none');
    } else {
        desktopAside.classList.remove('d-md-none');
        desktopAside.classList.add('d-md-block');
    }
    if (desktopAside.classList.contains('d-none')) {
        desktopAside.classList.remove('d-none');
        desktopAside.classList.add('d-block');
    } else {
        desktopAside.classList.remove('d-block');
        desktopAside.classList.add('d-none');
    }
    
}

toggleAside.addEventListener('click', toggleSidebar);

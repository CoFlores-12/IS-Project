const careerChangeBtn = document.getElementById('careerChangeBtn');
const careerChange = document.getElementById('careerChange');
const modalData = document.getElementById('modalData');
const modalDataBody = document.getElementById('modalDataBody');
const tableRequest = document.getElementById('tableRequest');
const academicModal = document.getElementById('academicModal');
const academicBtn = document.getElementById('academicBtn');
const careerChangeBS = new bootstrap.Modal(careerChange);
const academicModalBS = new bootstrap.Modal(academicModal);
const modalDataBS = new bootstrap.Modal(modalData);
const academicBody = document.getElementById("academicBody");
let dataCareerChange = [];
let toast = document.getElementById('toast');
let toastBody = document.getElementById('toastBody');
let toastTitle = document.getElementById('toastTitle');
let toastBS = new bootstrap.Toast(toast);

function validateRequest(value,request_id) {
  const retroRequest = document.getElementById('retroRequest');
  const btnValidateReq = document.getElementById('btnValidateReq');
  const btnValidateReq1 = document.getElementById('btnValidateReq1');
  const retroInvalid = document.getElementById('retroInvalid');
  if (retroRequest.value == "" && value == 0) {
    retroRequest.classList.add('border-danger');
    retroInvalid.classList.remove('d-none');
    return;
  }
  btnValidateReq.disabled = true;
  btnValidateReq1.disabled = true;

  const formData = new FormData();
  formData.append('value', value);
  formData.append('request_id',request_id)
  formData.append('response', retroRequest.value)

  fetch('/api/put/admin/validateRequest.php',{
    method: "POST",
    body: formData
  })
  .then(res=>{return res.json()})
  .then(res=>{
    if (!res.status) {
      throw new Error(res.message);
      
    }
    toastTitle.innerHTML ='Enroll Error'
    toastBody.innerHTML = `<div class="alert alert-success mb-0" role="alert">
        ${res.message}
    </div>`
    toastBS.show();
    modalDataBS.hide();
    GetMyRequests();
  })
  .catch(err=>{
    toastTitle.innerHTML ='Enroll Error'
    toastBody.innerHTML = `<div class="alert alert-danger mb-0" role="alert">
        ${err}
    </div>`
    toastBS.show();
    btnValidateReq.disabled = false;
    btnValidateReq1.disabled = false;
  })

}

function modalDataShow() {
    modalDataBS.show();
}
academicBtn.addEventListener('click', (e)=>{
    academicModalBS.show();
    fetch('/api/get/admin/academic.php')
    .then(res=>{return res.json()})
    .then(res=>{
        academicBody.innerHTML = ''
        const table = document.createElement("table");
        table.id = 'tableAcademic'
        table.className = "table table-bordered bg";
        const thead = document.createElement("thead");
        thead.className = "";
        thead.innerHTML = `
        <tr>
            <th class="bg text">ID Sección</th>
            <th class="bg text">Código de Clase</th>
            <th class="bg text">Nombre de Clase</th>
            <th class="bg text">Número de Empleado</th>
            <th class="bg text">Docente</th>
            <th class="bg text">Inscritos</th>
            <th class="bg text">Cupos</th>
            <th class="bg text">Aula</th>
            <th class="bg text">Edificio</th>
        </tr>
        `;
        table.appendChild(thead);
        const tbody = document.createElement("tbody");
        res.forEach(row => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td>${row.section_id}</td>
            <td>${row.class_code}</td>
            <td>${row.class_name}</td>
            <td>${row.employee_number}</td>
            <td>${row.teacher}</td>
            <td>${row.enrolled}</td>
            <td>${row.quotas}</td>
            <td>${row.classroom_name}</td>
            <td>${row.building_name}</td>
        `;
        tbody.appendChild(tr);
        });
        table.appendChild(tbody);

        academicBody.appendChild(table);
        
    })
});

function downloadExcel() {
    const table = document.querySelector("#tableAcademic");
    const tableHTML = table.outerHTML;
    
    const excelFileContent = `
      <html xmlns:o="urn:schemas-microsoft-com:office:office" 
            xmlns:x="urn:schemas-microsoft-com:office:excel" 
            xmlns="http://www.w3.org/TR/REC-html40">
      <head>
        <!-- Definir estilos si es necesario -->
        <meta charset="UTF-8">
      </head>
      <body>
        ${tableHTML}
      </body>
      </html>
    `;
  
    const blob = new Blob([excelFileContent], {
      type: "application/vnd.ms-excel",
    });
  
    const a = document.createElement("a");
    a.href = URL.createObjectURL(blob);
    a.download = "tabla_academica.xls";
    a.click();
  }
  

  function downloadPDF() {
    const table = document.querySelector("#tableAcademic");
    const tableHTML = table.outerHTML;
  
    const htmlContent = `
      <html>
        <head>
          <meta charset="UTF-8">
          <title>Información Académica</title>
          <style>
            table { border-collapse: collapse; width: 100%; }
            table, th, td { border: 1px solid black; text-align: left; padding: 8px; }
            th { background-color: #f2f2f2; }
          </style>
        </head>
        <body>
          <h3>Información Académica</h3>
          ${tableHTML}
        </body>
      </html>
    `;
  
    const iframe = document.createElement("iframe");
    document.body.appendChild(iframe);
    iframe.style.display = "none";
  
    const doc = iframe.contentWindow.document;
    doc.open();
    doc.write(htmlContent);
    doc.close();
  
    iframe.contentWindow.focus();
    iframe.contentWindow.print();
  
    setTimeout(() => document.body.removeChild(iframe), 1000);
  }
  

let btnSearchHistory = document.getElementById('btnSearchHistory');

let historyBody = document.getElementById('historyBody');
let inputHistory = document.getElementById('inputHistory');
        btnSearchHistory.addEventListener('click', ()=>{
            if (inputHistory.value === '') {return; }
            historyBody.innerHTML = `<center><div class="spinner-grow text-secondary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>`

            fetch('/api/get/admin/studentHistory.php?student_identifier='+inputHistory.value)
            .then((response)=>{return response.json()})
            .then((response)=>{
                let table = `<table class="table bg-aux mt-2">
                                <thead>
                                    <tr class="bg-aux text">
                                    <th class="bg-aux text" scope="col">Código</th>
                                    <th class="bg-aux text" scope="col">Clase</th>
                                    <th class="bg-aux text" scope="col">Nota</th>
                                    </tr>
                                </thead>
                                <tbody>`; 
                response.forEach(classHistory => {
                    table += `<tr class="bg-aux">
                            <th class="bg-aux text" scope="row">${classHistory['class_code']}</th>
                            <td class="bg-aux text">${classHistory['class_name']}</td>
                            <td class="bg-aux text">${classHistory['score']}</td>
                        </tr>`
                });

                table += `</tbody></table>`;
                historyBody.innerHTML = table
            })
            .catch(()=>{
                alert('Estudiante no encontrado')
            })
        })

function careerChangeClick(e) {
    modalDataShow();
    
    modalDataBody.innerHTML = `<p class="card-text placeholder-glow">
            <span class="placeholder col-7"></span>
            <span class="placeholder col-4"></span>
            <span class="placeholder col-4"></span>
            <span class="placeholder col-6"></span>
            <span class="placeholder col-8"></span>
          </p>`

    fetch('/api/get/admin/getRequest.php?id='+e)
    .then(res => {return res.text()})
    .then((res)=>{
        modalDataBody.innerHTML = res
    }).catch((err)=>{
    })
}
careerChangeBtn.addEventListener('click', (e)=>{
    careerChangeBS.show();
  GetMyRequests();
})

function GetMyRequests() {
  fetch('/api/get/admin/MyRequest.php')
    .then((res)=>{return res.json()})
    .then((res)=>{
        dataCareerChange = res;
        let html = ``;
        res.forEach(data => {
            html += `<tr onclick="careerChangeClick(${data.request_id})" class="careerChangeClickeable">
            <td>${data.local_time}</td>
            <td>${data.student_id}</td>
            <td>${data.title}</td>
            <td>${data.period}</td>
            <td><i class="bi bi-chevron-right"></i></td>
            </tr>`;
        });
        tableRequest.innerHTML = html;
    })
}

const toggleAside = document.getElementById('toggleAside');
const desktopAside = document.getElementById('desktopAside');

function toggleSidebar() {
    if (desktopAside.classList.contains('d-none')) {
        desktopAside.classList.remove('d-none');
        desktopAside.classList.remove('d-md-none');
        desktopAside.classList.add('d-block');
    } else {
        desktopAside.classList.remove('d-block');
        desktopAside.classList.add('d-md-none');
        desktopAside.classList.add('d-none');
    }
}

toggleAside.addEventListener('click', toggleSidebar);
const refreshIcon = document.getElementById('refreshIcon');
refreshChats.addEventListener('click', () => {
    refreshChats.classList.add('rotate');

    frameChats.contentWindow.location.reload();

    setTimeout(() => {
        refreshChats.classList.remove('rotate');
    }, 1000); 
});
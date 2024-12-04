let section = 0;
let toast = document.getElementById('toast');
let toastBody = document.getElementById('toastBody');
let toastTitle = document.getElementById('toastTitle');
let toastBS = new bootstrap.Toast(toast);

const params = new URLSearchParams(window.location.search);
const sectionId = params.get("section_id");
section = sectionId;
    getVideo(section)
    if (sectionId) {
        fetch(`/api/get/admin/getSectionTitle.php?section_id=${sectionId}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    document.getElementById("section-title").textContent = "Error: " + data.error;
                } else {
                    document.getElementById("section-title").textContent = data.title;
                }
            })
            .catch(error => {
                document.getElementById("section-title").textContent = "Error al cargar los datos";
                console.error("Error al consumir la API:", error);
            });

        // Llamada para cargar los estudiantes
        fetch(`/api/get/admin/getStudentsBySection.php?section_id=${sectionId}`)
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById("students-table-body");
                tableBody.innerHTML = ""; // Limpiar contenido previo

                if (data.error) {
                    tableBody.innerHTML = `<tr><td colspan="4" class="text-center">${data.error}</td></tr>`;
                    return;
                }

                if (data.length === 0) {
                    tableBody.innerHTML = `<tr><td colspan="4" class="text-center">No hay estudiantes en esta sección</td></tr>`;
                    return;
                }

                data.forEach((student, index) => {
                    const row = `
                        <tr>
                            <td class="bg-aux text">${index + 1}</td>
                            <td class="bg-aux text"><a href="http://localhost/views/students/profile/index.php?account_number=${student.account_number}">${student.full_name}</a></td>
                            <td class="bg-aux text">${student.account_number}</td>
                            <td class="bg-aux text">${student.institute_email}</td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            })
            .catch(error => {
                console.error("Error al consumir la API:", error);
                document.getElementById("students-table-body").innerHTML =
                    `<tr><td colspan="4" class="text-center">Error al cargar los datos</td></tr>`;
            });
    } else {
        document.getElementById("section-title").textContent = "Parámetro section_id no proporcionado";
    }

    const botonNewVideo = document.getElementById('addVideo');

    let addVideo = document.getElementById('modalVideo');
    let modalVideobn = new bootstrap.Modal(addVideo);

    modalVideobn.hide()

    if(botonNewVideo){
        botonNewVideo.addEventListener("click", ()=>{
            modalVideobn.show();
        })
    }
   

    const saveVideo = document.getElementById('saveVideo');
    let alertErrorVideo = document.getElementById('alertErrorVideo');
    alertErrorVideo.style.display = 'none';
    let validedVideo = document.getElementById('validedVideo');
    validedVideo.style.display = 'none';

    saveVideo.addEventListener('click', function() {
        const videoUrl = document.getElementById('videoUrl').value;
        const youtubeRegex = /(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/;
        const match = videoUrl.match(youtubeRegex);
        if (!match) {
            alertErrorVideo.style.display = "block";
            setTimeout(function() {
                alertErrorVideo.style.display = 'none';
            }, 3000)
            modalVideo.hide();
            return;
        }
        const videoId = match[1];
        const embedUrl = `https://www.youtube.com/embed/${videoId}`;
        guardarEnlace(section, embedUrl);
        videoUrl.value = "";
    });

    function guardarEnlace(sectionId, videoUrl) {
        fetch('/api/post/admin/addVideoSection.php', {
            method: 'POST',
            body: new URLSearchParams({
                section_id: sectionId,
                video_url: videoUrl
            }),
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status == "save") {
                validedVideo.style.display = "block";
                setTimeout(function() {
                    validedVideo.style.display = 'none';
                }, 3000)
            } else {
                alertErrorVideo.style.display = "block";
                setTimeout(function() {
                    alertErrorVideo.style.display = 'none';
                }, 3000)
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
    
    function getVideo(sectionId) {
        fetch(`/api/get/admin/getVideoSection.php?section_id=${sectionId}`)
            .then(response => response.json())
            .then(data => {
                if (data.video_url){
                    showVideo(data.video_url);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    function showVideo(videoUrl) {
        const videoContainer = document.getElementById('video-container');
        videoContainer.innerHTML = `<iframe src="${videoUrl}" title="YouTube video" allowfullscreen style="width: 100%; height: 100%;"></iframe>`;
    }
    function downloadExcel() {
        const table = document.querySelector("#studentsTable");
        const tableHTML = table.outerHTML;
        
        const excelFileContent = `
          <html xmlns:o="urn:schemas-microsoft-com:office:office" 
                xmlns:x="urn:schemas-microsoft-com:office:excel" 
                xmlns="http://www.w3.org/TR/REC-html40">
          <head>
            <style>
            .bg-custom-primary {background-color: #176b87 !important;}
            .text-white { color: #ffffff; }
            </style>
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
        a.download = `Estudiantes_seccion${sectionId}.xls`;
        a.click();
      }

function generatePdf() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    const table = document.getElementById('studentsTable');
    const rows = Array.from(table.querySelectorAll('tbody tr'));
    // Encabezado del documento
    doc.setFont("helvetica", "bold");
    doc.setFontSize(16);
    doc.text('Lista de Estudiantes', 105, 20, null, null, 'center'); // Centrado
    doc.setFontSize(12);
    doc.setFont("helvetica", "normal");
    doc.text(`Fecha: ${new Date().toLocaleDateString()}`, 10, 30);
    doc.text(`Sección: ${document.getElementById('section-title').textContent}`, 10, 40);
    // Línea divisoria
    doc.setLineWidth(0.5);
    doc.line(10, 45, 200, 45); // Línea horizontal
    // Datos de la tabla sin correo institucional y con 5 columnas para firmas
    const tableData = rows.map((row, rowIndex) => {
        const cells = Array.from(row.querySelectorAll('td'));
        return [
            rowIndex + 1,
            cells[1]?.textContent || '', // Nombre
            cells[2]?.textContent || '', // Núm. Cuenta
            '', '', '', '', '', '', // Espacios en blanco para las firmas (Lunes a Viernes)
        ];
    });
    // Configuración de la tabla
    doc.autoTable({
        head: [['#', 'Nombre', 'Núm. Cuenta', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes']],
        body: tableData,
        startY: 50, // Inicia después del encabezado
        theme: 'grid', // Tema de la tabla
        headStyles: {
            fillColor: [220, 220, 220], // Color de encabezado
            textColor: 0, // Texto negro
            fontStyle: 'bold',
        },
        bodyStyles: {
            textColor: [40, 40, 40], // Texto gris oscuro
        },
        alternateRowStyles: {
            fillColor: [245, 245, 245], // Filas alternadas
        },
        margin: { left: 10, right: 10 }, // Márgenes
    });
    // Descargar el archivo
    doc.save('Lista_de_Estudiantes.pdf');
}

function showVideo(videoUrl) {
    const videoContainer = document.getElementById('video-container');
    videoContainer.innerHTML = `<iframe src="${videoUrl}" title="YouTube video" allowfullscreen style="width: 100%; height: 100%;"></iframe>`;
}

const toggleAside = document.getElementById('toggleAside');
const desktopAside = document.getElementById('desktopAside');
const participantsBtn = document.getElementById('participantsBtn');
const participantsModal = document.getElementById('participantsModal');
const participantsModalBS = new bootstrap.Modal(participantsModal);
const scoresBtn = document.getElementById('scoresBtn');
const scoresModal = document.getElementById('scoresModal');
const scoresModalBS = new bootstrap.Modal(scoresModal);
const bodyTableScores = document.getElementById('bodyTableScores');

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
participantsBtn.addEventListener('click', ()=>{participantsModalBS.show()});
scoresBtn.addEventListener('click', ()=>{
    scoresModalBS.show();
    bodyTableScores.innerHTML = `<p class="card-text placeholder-glow">
            <span class="placeholder col-7"></span>
            <span class="placeholder col-4"></span>
            <span class="placeholder col-7"></span>
            <span class="placeholder col-4"></span>
            <span class="placeholder col-7"></span>
            <span class="placeholder col-4"></span>
        </p>`
    fetch('/api/get/class/getScores.php?section_id='+sectionId)
    .then(async res=>{
        if (!res.ok) throw new Error(await res.text());
        return res.text()})
    .then(res=>{
        bodyTableScores.innerHTML = res
    })
    .catch(err=>{
        bodyTableScores.innerHTML = `<div class="alert alert-danger" role="alert">
            ${err}
        </div>`

    })
});


function scoreEntered(input) {
    const value = input.value;
    const select = document.getElementById('select'+input.getAttribute('data-row-id'));
    if (value>=65) {
        select.value = 1;
        select.disabled = true;
    }else{
        select.value = 0;
        select.disabled = false;
        select.options[0].disabled = true;
        select.options[2].disabled = true;
        
    }
}

function saveScores(option) {
    document.querySelectorAll('.btnOptionScores').forEach(element => {
        element.disabled = true;
    });
    const formData = new FormData();
    formData.append('option', option);
    formData.append('section_id', sectionId);
    document.querySelectorAll('#bodyTableScores table tbody tr').forEach((row) => {
        const thElement = row.querySelector('th[data-id]');
        const studentId = thElement ? thElement.getAttribute('data-id') : "";
        const scoreInput = row.querySelector('input[type="number"]');
        const score = scoreInput ? scoreInput.value : "";
        const selectElement = row.querySelector('select');
        const obsId = selectElement ? selectElement.value : "";
        const data = {
            score: score,
            obs_id: obsId
        };
        formData.append(studentId, JSON.stringify(data));
    });
    fetch('/api/put/teacher/updateScores.php',{
        method: 'POST',
        body: formData
    })
    .then(res=>{return res.json()})
    .then(res=>{
        if (!res.status) throw new Error(res.message);
        scoresModalBS.hide();
        toastTitle.innerHTML ='Calificaciones guardadas'
        toastBody.innerHTML = `<div class="alert alert-success mb-0" role="alert">
            ${res.message}
        </div>`
        toastBS.show();
    })
    .catch(err=>{
        console.log(err);
        
        document.querySelectorAll('.btnOptionScores').forEach(element => {
            element.disabled = false;
        });
        toastTitle.innerHTML ='Error'
        toastBody.innerHTML = `<div class="alert alert-danger mb-0" role="alert">
            ${err}
        </div>`
        toastBS.show();
    })
}



function save() {

    let alertErrorSendSurvey = document.getElementById('alertErrorSendSurvey');
    alertErrorSendSurvey.style.display = 'none';

    sendSurvey = document.getElementById("sendSurvey");
    alertSendSurvey = document.getElementById("alertSendSurvey");
    alertSendSurvey.style.display = "none";

    sendSurvey.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Enviando...`;
    sendSurvey.disabled = true;

    const pregunta1 = document.getElementById("pregunta1").value;
    const pregunta2 = document.getElementById("pregunta2").value;
    const question3 = document.getElementById("justificacion").value;

    if (!pregunta1 || !pregunta2 || !question3) {
        alertErrorSendSurvey.style.display = "block";
        alertErrorSendSurvey.removeAttribute('hidden');
        sendSurvey.innerHTML = `Enviar`;
    sendSurvey.disabled = false;
        return;
    }

    const responses = {
        question_1: pregunta1,
        question_2: pregunta2,
        question_3: question3
    };
    const responsesJSON = JSON.stringify(responses);

    const formData = new FormData();

    formData.append('responses', responsesJSON);
    formData.append('section_id', sectionId);

    fetch('/api/post/students/saveEvaluationTeacher.php', {
        method: 'POST',
        body: formData,
    })
        .then((res) => res.json()) // Parseamos la respuesta JSON
        .then((res) => {
            if (!res.status) throw new Error(res.message); // Validamos el estado de la respuesta
            scoresModalBS.hide();
            // Opcional: limpiar los campos después de enviar
            alertSendSurvey.removeAttribute('hidden');
            alertSendSurvey.style.display = "block";
            setTimeout(function() {
                alertSendSurvey.style.display = 'none';
            }, 3000)
            document.getElementById('pregunta1').value = '';
            document.getElementById('pregunta2').value = '';
            document.getElementById('justificacion').value = '';
        })
        .catch((err) => {
            console.error(err); // Registramos el error en la consola
        });
        



   
}
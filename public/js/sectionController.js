let section = 0;

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
                            <td class="bg-aux text">${student.full_name}</td>
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

// Lógica de roles para mostrar los botones de "Agregar Video" y "Descargar Lista de Estudiantes"

fetch('/api/get/admin/getUserRole.php')
    .then(response => response.json())
    .then(data => {
        const teacherInfoDiv = document.getElementById("teacher-profile");
        const downloadPdfButton = document.getElementById("downloadPdf");
        const buttonVideoDiv = document.getElementById("butonVideo");

        if (data.role === 'Teacher' || data.role === 'Coordinator' || data.role === 'Department Head') {
            // Mostrar los botones (Agregar Video y Descargar PDF)
            buttonVideo();
            downloadPdfButton.classList.remove('d-none');
            
            // Ocultar la información del docente
            teacherInfoDiv.classList.add('d-none');
        } else {
            // Ocultar los botones (Agregar Video y Descargar PDF)
            downloadPdfButton.classList.add('d-none');
            
            // Mostrar la información del docente
            teacherInfoDiv.classList.remove('d-none');
        }
    })
    .catch(error => console.error('Error obteniendo rol:', error));


    const div = document.getElementById('butonVideo');
    function buttonVideo(){
        const modalVideo = new bootstrap.Modal(document.getElementById('modalVideo'));
        const boton = document.createElement('button');
        boton.textContent = 'Agregar video'; 
        boton.className = 'btn btn-success'; 
        boton.id = 'addVideo'; 
        div.appendChild(boton);
        boton.addEventListener('click', function() {
            modalVideo.show();
        });
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

    document.addEventListener('DOMContentLoaded', () => {
        // Obtener sectionId de la URL
        const urlParams = new URLSearchParams(window.location.search);
        const sectionId = urlParams.get('section_id');

        if (sectionId) {
            getVideo(sectionId);
        } else {
            console.error('No se proporcionó un section_id en la URL.');
        }
    });

document.addEventListener("DOMContentLoaded", function () {
    const params = new URLSearchParams(window.location.search);
    const sectionId = params.get("section_id");

    // Verificar si el parámetro section_id está presente
    if (!sectionId) {
        document.getElementById("teacher-name").textContent = "Parámetro section_id no proporcionado";
        return;  // Salir si no se encuentra el parámetro section_id
    }

    // Obtener el nombre del docente
    fetch(`/api/get/admin/getTeacherById.php?section_id=${sectionId}`)
        .then(response => {
            // Verificar si la respuesta es exitosa (código 200)
            if (!response.ok) {
                throw new Error("Error al obtener los datos del servidor");
            }
            return response.json();
        })
        .then(data => {
            // Verificar si el servidor ha devuelto un error
            if (data.error) {
                document.getElementById("teacher-name").textContent = "Error: " + data.error;
                console.error("Error del servidor:", data.error);
            } else {
                document.getElementById("teacher-name").textContent = data.teacher_name;
                console.log("Datos del docente:", data.teacher_name);
            }
        })
        .catch(error => {
            // Mostrar el mensaje de error en el UI y en la consola
            document.getElementById("teacher-name").textContent = "Error al cargar los datos";
            console.error("Error al consumir la API:", error);
        });
});

document.addEventListener("DOMContentLoaded", function () {
    const sectionId = new URLSearchParams(window.location.search).get("section_id");
    const teacherProfileLink = document.getElementById("viewTeacherProfile");

    // Obtener el employee_number a partir del section_id
    fetch(`/api/get/admin/getTeacherBySectionId.php?section_id=${sectionId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error("Error al obtener el docente por sección", data.error);
            } else {
                const employeeNumber = data.employee_number;  // Asegúrate de que este campo esté presente en la respuesta

                // Redirigir a la URL con el employee_number
                teacherProfileLink.href = `/views/admin/teacher/profile/index.php?employee_number=${employeeNumber}`;
            }
        })
        .catch(error => {
            console.error("Error al consumir la API:", error);
        });
});

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
// Evento del botón
document.getElementById('downloadPdf').addEventListener('click', generatePdf);

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

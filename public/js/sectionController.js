let section = 0;

document.addEventListener("DOMContentLoaded", function () {
    const params = new URLSearchParams(window.location.search);
    const sectionId = params.get("section_id");
    section = sectionId;

    // Obtener y mostrar el título de la sección
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
});

// Función para manejar el botón de agregar video
function buttonVideo() {
    const modalVideo = new bootstrap.Modal(document.getElementById('modalVideo'));
    const botonExistente = document.getElementById('addVideo');

    // Evitar duplicación del botón
    if (botonExistente) {
        return; // Si el botón ya existe, no hacemos nada
    }

    const boton = document.createElement('button');

    boton.textContent = 'Agregar video'; 
    boton.className = 'btn btn-success'; 
    boton.id = 'addVideo'; 

    document.getElementById('butonVideo').appendChild(boton);

    boton.addEventListener('click', function () {
        modalVideo.show();
    });
}

const saveVideo = document.getElementById('saveVideo');

saveVideo.addEventListener('click', function () {
    const videoUrl = document.getElementById('videoUrl').value;
    const youtubeRegex = /(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/;
    const match = videoUrl.match(youtubeRegex);

    if (!match) {
        document.getElementById('alertErrorVideo').style.display = "block";
        setTimeout(() => document.getElementById('alertErrorVideo').style.display = 'none', 3000);
        return;
    }

    const videoId = match[1];
    const embedUrl = `https://www.youtube.com/embed/${videoId}`;

    guardarEnlace(section, embedUrl);
});

function guardarEnlace(sectionId, videoUrl) {
    fetch('/api/post/admin/addVideoSection.php', {
        method: 'POST',
        body: new URLSearchParams({
            section_id: sectionId,
            video_url: videoUrl
        }),
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === "save") {
                document.getElementById('validedVideo').style.display = "block";
                setTimeout(() => document.getElementById('validedVideo').style.display = 'none', 3000);
            } else {
                document.getElementById('alertErrorVideo').style.display = "block";
                setTimeout(() => document.getElementById('alertErrorVideo').style.display = 'none', 3000);
            }
        })
        .catch(error => console.error('Error:', error));
}

function getVideo(sectionId) {
    fetch(`/api/get/admin/getVideoSection.php?section_id=${sectionId}`)
        .then(response => response.json())
        .then(data => {
            if (data.video_url) {
                document.getElementById('video-container').innerHTML = 
                    `<iframe src="${data.video_url}" title="YouTube video" allowfullscreen style="width: 100%; height: 100%;"></iframe>`;
            }
        })
        .catch(error => console.error('Error:', error));
}

// Lógica de roles para mostrar los botones de "Agregar Video" y "Descargar Lista de Estudiantes"
fetch('/api/get/admin/getUserRole.php')
    .then(response => response.json())
    .then(data => {
        if (data.role === 'Teacher' || data.role === 'Coordinator' || data.role === 'Department Head') {
            buttonVideo();
            document.getElementById('downloadPdf').classList.remove('d-none');
        }
    })
    .catch(error => console.error('Error obteniendo rol:', error));

// Función para generar el PDF con los cambios solicitados
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

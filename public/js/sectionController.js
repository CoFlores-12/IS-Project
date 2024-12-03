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


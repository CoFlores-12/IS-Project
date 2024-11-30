let section = 0;

document.addEventListener("DOMContentLoaded", function () {
    // Obtener el parámetro section_id de la URL
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
                    // Actualizar el contenido del h4 con el título dinámico
                    document.getElementById("section-title").textContent = data.title;
                }
            })
            .catch(error => {
                document.getElementById("section-title").textContent = "Error al cargar los datos";
                console.error("Error al consumir la API:", error);
            });
    } else {
        document.getElementById("section-title").textContent = "Parámetro section_id no proporcionado";
    }
});


fetch('/api/get/admin/getUserRole.php')
    .then(response => response.json())
    .then(data => {
        if (data.role === 'Teacher' || data.role === 'Coordinator' || data.role === 'Department Head') {
            buttonVideo();
        } else if (data.role === 'Student') {

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
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
    } else {
        document.getElementById("section-title").textContent = "Parámetro section_id no proporcionado";
    }

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
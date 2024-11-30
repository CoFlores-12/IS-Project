const careerChangeBtn = document.getElementById('careerChangeBtn');
const careerChange = document.getElementById('careerChange');
const modalData = document.getElementById('modalData');
const modalDataBody = document.getElementById('modalDataBody');
const tableRequest = document.getElementById('tableRequest');
const careerChangeBS = new bootstrap.Modal(careerChange);
const modalDataBS = new bootstrap.Modal(modalData);
let dataCareerChange = [];
function modalDataShow() {
    modalDataBS.show();
}
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
})

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
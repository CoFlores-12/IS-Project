const careerChangeBtn = document.getElementById('careerChangeBtn');
const careerChange = document.getElementById('careerChange');
const modalData = document.getElementById('modalData');
const modalDataBody = document.getElementById('modalDataBody');
const careerChangeBody = document.getElementById('careerChangeBody');
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
}
careerChangeBtn.addEventListener('click', (e)=>{
    careerChangeBS.show();
    fetch('/api/get/admin/careerChangeRequest.php')
    .then((res)=>{return res.json()})
    .then((res)=>{
        dataCareerChange = res;
        let html = `<table class="my-2 table">
            <thead>
              <tr>
                <td>Account number</td>
                <td>Student name</td>
                <td>Date</td>
              </tr>
            </thead><tbody>`;
        res.forEach((data, index) => {
            html += `<tr onclick="careerChangeClick(${index})" class="careerChangeClickeable">
            <td>${data.student_id}</td>
            <td>${data.student_name}</td>
            <td>${data.date}</td>
            <td><i class="bi bi-chevron-right"></i></td>
            </tr>`;
        });
        careerChangeBody.innerHTML = html + '</tbody></table>';
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
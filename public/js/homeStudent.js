let btnModalRequests = document.getElementById('btnModalRequests');
let btnModalEnrollment = document.getElementById('btnModalEnrollment');
let cancelEnrollmentBtn = document.getElementById('cancelEnrollmentBtn');
let addEnrollmentBtn = document.getElementById('addEnrollmentBtn');
let modalRequests = document.getElementById('modalRequests');
let modalEnrollment = document.getElementById('modalEnrolment');
let requestType = document.getElementById('requestType');
let dataForRequest = document.getElementById('dataForRequest');
let formDataEnrollment = document.getElementById('form-data');
let enrollBtn = document.getElementById('enrollBtn');
let toast = document.getElementById('toast');
let toastBody = document.getElementById('toastBody');
let toastTitle = document.getElementById('toastTitle');
let toastBS = new bootstrap.Toast(toast)
let modalRequestsBS = new bootstrap.Modal(modalRequests);
let modalEnrollmentBS = new bootstrap.Modal(modalEnrollment);

let optionsBody = async (value) => {
    let body = `<textarea name="comments" placeholder="Justify" id="comments" class="form-control bg-aux my-4 text"></textarea>`;
    switch (value) {
        case "2": 
            body+= '<input type="file" name="evidemce"  accept="application/pdf"  class="form-control my-4" id="evidence">';
            break
        case "3": 
            let HTML = `<select name="careerChange" class="form-control my-4" id="careerChange">
            <option value="">Select Career</option>`
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
            <option value="">Select Campus</option>`
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
    body += `<button id="sendRequestBtn" class="btn bg-custom-primary text mt-2 form-control">Send</button>`;
    
    return body
    
}

btnModalRequests.addEventListener('click', (e)=>{
    modalRequestsBS.show();
})
btnModalEnrollment.addEventListener('click', (e)=>{
    modalEnrollmentBS.show();
    getClasses();
})

var selected;
var selectedSection;

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
                        <td>Section</td>
                        <td>Quotas</td>
                        <td>Days</td>
                        <td>Reacher</td>`;
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
        var html = `<table id="table" class="mt-4">`;
        res.forEach(element => {
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
}

cancelEnrollmentBtn.addEventListener('click', (e)=>{
    e.target.classList.add('active');
    e.target.classList.add('bg-aux');
    addEnrollmentBtn.classList.remove('active');
    addEnrollmentBtn.classList.remove('bg-aux');
    formDataEnrollment.innerHTML = '<center><div class="spinner-border text m-4" role="status"></div></center>';
    
})
addEnrollmentBtn.addEventListener('click', (e)=>{
    e.target.classList.add('active');
    e.target.classList.add('bg-aux');
    cancelEnrollmentBtn.classList.remove('active');
    cancelEnrollmentBtn.classList.remove('bg-aux');
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
        try {
            formData.append("evidence", document.getElementById('evidence').files[0])
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
            alert(response.message);
            e.target.innerHTML = `Send`;
            e.target.disabled = false;
            dataForRequest.innerHTML = '';
            modalRequestsBS.hide();
        })
        .catch(()=>{
            alert('error');
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
        if (res.status) {
            toastTitle.innerHTML ='Enroll succes'
            toastBody.innerHTML = `<div class="alert alert-success mb-0" role="alert">
                ${res.message}
            </div>`
            toastBS.show();
            tableSections.innerHTML = '';
        }else {
            toastTitle.innerHTML ='Enroll error'
            toastBody.innerHTML = `<div class="alert alert-danger mb-0" role="alert">
                ${res.message}
            </div>`
            toastBS.show();
        }
    })
})

const roleAdminBtn = document.getElementById('roleAdministrationBtn');
const roleAdminModal = new bootstrap.Modal(document.getElementById('roleAdminModal'));
const editRoleModal = new bootstrap.Modal(document.getElementById('editRoleModal'));
let selectedPersonId = null;

// Cargar la lista de roles al abrir la modal de administración
roleAdminBtn.addEventListener('click', () => {
    fetch('/api/get/admin/employees.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('roleAdminTableBody');
            tableBody.innerHTML = '';
            data.forEach(row => {
                tableBody.innerHTML += `
                    <tr>
                        <td>${row.person_id}</td>
                        <td>${row.first_name}</td>
                        <td>${row.last_name}</td>
                        <td>${row.institute_email}</td>
                        <td>${row.role_type}</td>
                        <td><button class="btn btn-primary editRoleBtn" data-person-id="${row.person_id}">Edit</button></td>
                    </tr>
                `;
            });

            document.querySelectorAll('.editRoleBtn').forEach(button => {
                button.addEventListener('click', () => {
                    selectedPersonId = button.getAttribute('data-person-id');
                    loadRoleOptions();
                    editRoleModal.show();
                });
            });
        });
});

// Cargar opciones de roles en el dropdown
function loadRoleOptions() {
    fetch('/api/get/admin/roles.php')
        .then(response => response.json())
        .then(data => {
            const roleDropdown = document.getElementById('roleDropdown');
            roleDropdown.innerHTML = '';
            data.forEach(role => {
                roleDropdown.innerHTML += `<option value="${role.role_id}">${role.type}</option>`;
            });
        });
}

// Guardar cambios en el rol
document.getElementById('editRoleForm').addEventListener('submit', (e) => {
    e.preventDefault();
    const newRoleId = document.getElementById('roleDropdown').value;

    fetch('/api/post/admin/editEmployeeRole.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ person_id: selectedPersonId, role_id: newRoleId })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Role updated successfully!');
                editRoleModal.hide();
                roleAdminBtn.click(); // Recargar tabla
            } else {
                alert('Error updating role: ' + data.error);
            }
        });
});





(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
        form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
        });
    }, false);
    })();
    
    const newUserModal = document.getElementById('newUserModal');
    const newUserModalBS = new bootstrap.Modal(newUserModal)
    const SRPModal = document.getElementById('SRP');
    const SRPModalBS = new bootstrap.Modal(SRPModal)
    const addUserBtn = document.getElementById('addUserBtn');
    const departamentSelect = document.getElementById('departamentSelect');
    const saveSRPBtn = document.getElementById('saveSRPBtn');

    addUserBtn.addEventListener('click', ()=>{
        newUserModalBS.show();
        fetch('/api/get/public/allDepartaments.php')
        .then((response) => {return response.json()})
        .then((response) => {
            departamentSelect.innerHTML = '<option value="">Select department...</option>';
            response.forEach(department => {
                departamentSelect.innerHTML += `<option value="${department.department_id}">${department.department_name}</option>`;
            });
        })
    })
    saveSRPBtn.addEventListener('click', (e)=>{
        e.target.disabled = true;
        e.target.innerHTML = `<div class="spinner-border text-light" role="status"></div>`;
        let formData = new FormData();
        formData.append('endTime', document.getElementById('end-time').value);
        formData.append('startTime', document.getElementById('start-time').value);
        fetch('/api/put/admin/registrationPeriod.php',{
            method: 'POST',
            body: formData
        })
        .then((response)=>{return response})
        .then((response)=>{
            alert('Done!');
            SRPModalBS.hide();
            saveSRPBtn.disabled = false;
            saveSRPBtn.innerHTML = 'save'
        })
    })

    const modalExceptionalCancellation = document.getElementById('modalExceptionalCancellation');
    const modalCCE = new bootstrap.Modal(modalExceptionalCancellation)
    const saveECCBtn = document.getElementById('saveECCBtn');
    let starExceptional = document.getElementById('start-time-exceptional');
    let endExceptional = document.getElementById('end-time-exceptional');


    saveECCBtn.addEventListener('click', ()=>{
   
        saveECCBtn.innerHTML = `<div class="spinner-border text-light" role="status"></div>`;


        if(starExceptional.value == "" || endExceptional.value == ""){
            return
        }else{

            const formData = new FormData();
            formData.append('start_time', starExceptional.value);
            formData.append('end_time', endExceptional.value);


            fetch('/api/put/admin/exceptionalCancellation.php',{
            method: 'POST',
            body: formData
            })
            .then((response)=>{return response})
            .then((response)=>{
                modalCCE.hide();
                saveECCBtn.disabled = false;
                saveECCBtn.innerHTML = 'save'
            })
        }

        
    })

fetch('/api/get/admin/adminData.php')
.then((res)=>{return res.json()})
.then((res)=>{
    console.log(res);
    document.getElementById('RegionalCenter').innerHTML = `<strong>${res.Regional_center}</strong>`
    document.getElementById('Teachers').innerHTML = `<strong>${res.Teachers}</strong>`
    document.getElementById('Students').innerHTML = `<strong>${res.Students}</strong>`
    document.getElementById('Careers').innerHTML = `<strong>${res.Careers}</strong>`
    document.getElementById('inputStartR').innerHTML = `
    <input
        type="datetime-local"
        id="start-time"
        value="${res.registrationPeriod.startTime}"
        class="form-control bg-aux"
        name="start-time"
    />`;
    document.getElementById('inputEndR').innerHTML = `
    <input
        type="datetime-local"
        id="start-time"
        value="${res.registrationPeriod.endTime}"
        class="form-control bg-aux"
        name="start-time"
    />`;
})
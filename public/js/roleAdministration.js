const roleAdminBtn = document.getElementById('roleAdministrationBtn');
const roleAdminModal = new bootstrap.Modal(document.getElementById('roleAdminModal'));
const editRoleModal = new bootstrap.Modal(document.getElementById('editRoleModal'));
let toast = document.getElementById('toast');
let toastBody = document.getElementById('toastBody');
let toastTitle = document.getElementById('toastTitle');
let toastBS = new bootstrap.Toast(toast);
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

var form = document.getElementById('newUSerForm');
form.addEventListener('submit',async function(event) {
    event.preventDefault();
    const identityInput = document.getElementById('identity');
    const createUserBtn = document.getElementById('createUserBtn');
    const isIdentityValid = validateHondurasID(identityInput.value);
    
    if (!isIdentityValid) {
        identityInput.classList.add("is-invalid");
        identityInput.classList.remove("is-valid");
        return;
    }

    if (!form.checkValidity() || !isIdentityValid) {
        form.classList.add("was-validated");
        return; // Detener si el formulario no es válido
    }
    createUserBtn.disabled = true;
    createUserBtn.innerHTML = '<div class="spinner-border text-secondary" role="status"></div>'

    const formData = new FormData(form);
    try {
        fetch("/api/post/admin/addUser.php", {
            method: "POST",
            body: formData
        })
        .then((res)=>{
            return res.json()})
            .then((res)=>{
            createUserBtn.disabled = false;
            createUserBtn.innerHTML = 'Crear Usuario'
            if (res.status) {
                

                toastTitle.innerHTML ='Usuario creado exitoso'
                toastBody.innerHTML = `<div class="alert alert-success mb-0" role="alert">
                ${res.message}
                </div>`
                toastBS.show();
                const form = document.getElementById('newUSerForm');
                form.reset();
                form.classList.remove("was-validated");
                newUserModalBS.hide();
            }else {
                
                toastTitle.innerHTML ='error al crear usuario'
                toastBody.innerHTML = `<div class="alert alert-danger mb-0" role="alert">
                    ${res.message}
                </div>`
                toastBS.show();
            }
        })

    } catch (error) {
        
        createUserBtn.disabled = false;
        createUserBtn.innerHTML = 'Crear Usuario'
        console.error("Error:", error);
    }
});

// Datos de departamentos y municipios
const departments = {
    "01": ["0101", "0102", "0103", "0104", "0105", "0106", "0107", "0108"], // Atlántida
    "02": ["0201", "0202", "0203", "0204", "0205", "0206", "0207", "0208", "0209", "0210"], // Colón
    "03": [
        "0301", "0302", "0303", "0304", "0305", "0306", "0307", "0308", "0309", "0310",
        "0311", "0312", "0313", "0314", "0315", "0316", "0317", "0318", "0319", "0320", "0321"
    ], // Comayagua
    "04": ["0401", "0402", "0403", "0404", "0405", "0406", "0407", "0408", "0409"], // Copán
    "05": ["0501", "0502", "0503", "0504", "0505", "0506", "0507", "0508", "0509"], // Cortés
    "06": ["0601", "0602", "0603", "0604", "0605", "0606", "0607"], // Choluteca
    "07": ["0701", "0702", "0703", "0704", "0705", "0706", "0707"], // El Paraíso
    "08": [
        "0801", "0802", "0803", "0804", "0805", "0806", "0807", "0808", "0809", "0810",
        "0811", "0812", "0813", "0814", "0815", "0816", "0817", "0818"
    ], // Francisco Morazán
    "09": ["0901", "0902", "0903", "0904", "0905", "0906", "0907", "0908", "0909"], // Gracias a Dios
    "10": ["1001", "1002", "1003", "1004", "1005", "1006", "1007", "1008"], // Intibucá
    "11": ["1101", "1102", "1103", "1104", "1105", "1106", "1107", "1108", "1109", "1110"], // Islas de la Bahía
    "12": ["1201", "1202", "1203", "1204", "1205", "1206"], // La Paz
    "13": ["1301", "1302", "1303", "1304", "1305", "1306", "1307", "1308", "1309", "1310"], // Lempira
    "14": ["1401", "1402", "1403", "1404", "1405", "1406", "1407", "1408", "1409", "1410"], // Ocotepeque
    "15": [
        "1501", "1502", "1503", "1504", "1505", "1506", "1507", "1508", "1509", "1510",
        "1511", "1512", "1513", "1514", "1515"
    ], // Olancho
    "16": ["1601", "1602", "1603", "1604", "1605", "1606", "1607", "1608", "1609"], // Santa Bárbara
    "17": ["1701", "1702", "1703", "1704", "1705", "1706", "1707", "1708"], // Valle
    "18": [
        "1801", "1802", "1803", "1804", "1805", "1806", "1807", "1808", "1809", "1810",
        "1811", "1812", "1813", "1814", "1815", "1816", "1817", "1818"
    ], // Yoro
};


// Función para validar número de identidad
function validateHondurasID(id) {
    // Eliminar guiones y caracteres no numéricos
    const cleanedId = id.replace(/-/g, "").trim();
    
    

    // Separar las secciones
    const departmentCode = cleanedId.substring(0, 2);
    const municipalityCode = cleanedId.substring(0, 4);
    const year = cleanedId.substring(4, 8);
    const correlativo = cleanedId.substring(8, 13);
    const feedback = document.getElementById('identityFeedback');
    // Validar que el departamento exista
    if (!departments[departmentCode]){ 
        feedback.textContent = 'El código de departamento es incorrecto';
        return false}else{
            feedback.textContent = '';

        };
        
        // Validar que el municipio exista
    if (!departments[departmentCode].includes(municipalityCode)) {
        feedback.textContent = 'El código de municipio es incorrecto';
        return false}else{
            feedback.textContent = '';

        };
        
        // Validar año de inscripción (1900-2014)
        const yearNumber = parseInt(year, 10);
        if (yearNumber < 1900 || yearNumber > new Date().getFullYear()-10){ 
        feedback.textContent = 'El año de inscripción es incorrecto';
        return false}else{
            feedback.textContent = '';

        };

    // Validar correlativo (debe ser numérico y no estar vacío)
    if (!/^\d{5}$/.test(correlativo)) return false;

    return true;
}

const identityInput = document.getElementById('identity');

document.getElementById('identity').addEventListener('input', function (e) {
    let input = e.target;
    let value = input.value.replace(/\D/g, ''); // Solo números
    let formattedValue = '';

    // Formatear el valor con guiones
    if (value.length > 0) formattedValue = value.substring(0, 4);
    if (value.length > 4) formattedValue += '-' + value.substring(4, 8);
    if (value.length > 8) formattedValue += '-' + value.substring(8, 13);

    input.value = formattedValue;
    
    const feedback = document.getElementById('identityFeedback');
    
    const isValid = validateHondurasID(formattedValue);
    if (isValid) {
        feedback.textContent = '';
        identityInput.classList.remove('is-invalid');
        identityInput.classList.add('is-valid');
    } else {
        identityInput.classList.add('is-invalid');
        identityInput.classList.remove('is-valid');
    }
});

const newUserModal = document.getElementById('newUserModal');
const newUserModalBS = new bootstrap.Modal(newUserModal)
const SRPModal = document.getElementById('SRP');
const enrollPeriod = document.getElementById('enrollPeriod');
const enrollPeriodBS = new bootstrap.Modal(enrollPeriod)
const SRPModalBS = new bootstrap.Modal(SRPModal)
const addUserBtn = document.getElementById('addUserBtn');
const departamentSelect = document.getElementById('departamentSelect');
const saveSRPBtn = document.getElementById('saveSRPBtn');
const saveEnrollPeriodBtn = document.getElementById('saveEnrollPeriodBtn');


addUserBtn.addEventListener('click', ()=>{
    newUserModalBS.show();
    fetch('/api/get/public/allDepartaments.php')
    .then((response) => {return response.json()})
    .then((response) => {
        departamentSelect.innerHTML = '<option value="">Seleccione un departamento...</option>';
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
        saveSRPBtn.disabled = false;
        saveSRPBtn.innerHTML = 'Guardar'
    })
})
saveEnrollPeriodBtn.addEventListener('click', (e)=>{
    e.target.disabled = true;
    e.target.innerHTML = `<div class="spinner-border text-light" role="status"></div>`;
    let formData = new FormData();
    formData.append('EnrollIn1S', document.getElementById('periodo1Inicio').value);
    formData.append('EnrollIn1F', document.getElementById('periodo1Fin').value);

    formData.append('EnrollIn2S', document.getElementById('periodo2Inicio').value);
    formData.append('EnrollIn2F', document.getElementById('periodo2Fin').value);

    formData.append('EnrollIn3S', document.getElementById('periodo3Inicio').value);
    formData.append('EnrollIn3F', document.getElementById('periodo3Fin').value);
    fetch('/api/put/admin/enrollPeriod.php',{
        method: 'POST',
        body: formData
    })
    .then((response)=>{return response})
    .then((response)=>{
        e.target.disabled = false;
        e.target.innerHTML = 'Guardar'
        toastTitle.innerHTML =''
        toastBody.innerHTML = `<div class="alert alert-success mb-0" role="alert">
            Periodo de matricula actualizado exitosamente!
        </div>`
        toastBS.show();
        enrollPeriodBS.hide();
    })
    .catch(error=>{
        e.target.disabled = false;
        e.target.innerHTML = 'Guardar';
        toastTitle.innerHTML =''
        toastBody.innerHTML = `<div class="alert alert-danger mb-0" role="alert">
            Error en servidor
        </div>`
        toastBS.show();

    })
})



let alertUpdateCanceled = document.getElementById('alerCanceled');
        
alertUpdateCanceled.style.display = 'none';

saveECBtn.addEventListener("click", ()=>{
    saveECBtn.innerHTML = `<div class="spinner-border text-light" role="status"></div>`;

    let starExceptional = document.getElementById('start-time-exceptional');
    let endExceptional = document.getElementById('end-time-exceptional');

        const formData = new FormData();
        formData.append('start_time', starExceptional.value);
        formData.append('end_time', endExceptional.value);


        fetch('/api/put/admin/exceptionalCancellation.php',{
        method: 'POST',
        body: formData
        })
        .then((response)=>{return response})
        .then((response)=>{
            alertUpdateCanceled.removeAttribute('hidden');
            alertUpdateCanceled.style.display = "block";
            setTimeout(function() {
                alertUpdateCanceled.style.display = 'none';
              }, 3000)

            saveECBtn.disabled = false;
            saveECBtn.innerHTML = 'Guardar'
        })
    
})


let alerUploadNotes = document.getElementById('alerUploadNotes');
let newPACbtn = document.getElementById('newPACbtn');
        
alerUploadNotes.style.display = 'none';

saveUNBtn.addEventListener("click", ()=>{
    saveUNBtn.innerHTML = `<div class="spinner-border text-light" role="status"></div>`;

    let starExceptional = document.getElementById('start-time-uploadnotes');
    let endExceptional = document.getElementById('end-time-uploadnotes');

    console.log(starExceptional.value, "   -   ", endExceptional.value)

        const formData = new FormData();
        formData.append('start_time', starExceptional.value);
        formData.append('end_time', endExceptional.value);


        fetch('/api/put/admin/uploadNotesPeriod.php',{
        method: 'POST',
        body: formData
        })
        .then((response)=>{return response})
        .then((response)=>{
            alerUploadNotes.removeAttribute('hidden');
            alerUploadNotes.style.display = "block";
            setTimeout(function() {
                alerUploadNotes.style.display = 'none';
              }, 3000)

            saveUNBtn.disabled = false;
            saveUNBtn.innerHTML = 'Guardar'
        })
    
})
newPACbtn.addEventListener("click", ()=>{
    newPACbtn.disabled = true;
    newPACbtn.innerHTML = `<div class="spinner-border text-light" role="status"></div>`;

        fetch('/api/put/admin/updatePAC.php',{
            method: 'PUT'
        })
        .then((res)=>{return res.json()})
        .then((res)=>{
             document.getElementById('PACNOW').innerHTML = `<strong>${res.message}</strong>`
            newPACbtn.disabled = false;
            newPACbtn.innerHTML = 'Generar nuevo periodo académico'
        })
        .catch(err=>{
            newPACbtn.disabled = false;
            newPACbtn.innerHTML = 'Generar nuevo periodo académico'

        })
    
})


let logs = [];
  const roles = {
    0: "Administrador",
    1: "Admisiones",
    2: "Registro",
    3: "Docentes",
    4: "Coordinador",
    7: "Estudiantes"
  };

  let filteredLogs = [...logs];
  let currentPage = 1;
  let rowsPerPage = 10;
fetch('/api/get/admin/adminData.php')
.then((res)=>{return res.json()})
.then((res)=>{
    document.getElementById('RegionalCenter').innerHTML = `<strong>${res.Regional_center}</strong>`
    document.getElementById('Teachers').innerHTML = `<strong>${res.Teachers}</strong>`
    document.getElementById('Students').innerHTML = `<strong>${res.Students}</strong>`
    document.getElementById('Careers').innerHTML = `<strong>${res.Careers}</strong>`
    document.getElementById('PACNOW').innerHTML = `<strong>${res.PACNOW}</strong>`
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
        id="end-time"
        value="${res.registrationPeriod.endTime}"
        class="form-control bg-aux"
        name="start-time"
    />`;
    document.getElementById('start-time-cancaled').innerHTML = `
    <input
        type="datetime-local"
        id="start-time-exceptional"
        value="${res.cancellationExceptional.startTime}"
        class="form-control bg-aux"
        name="start-time"
    />`;
    document.getElementById('end-time-cancaled').innerHTML = `
    <input
        type="datetime-local"
        id="end-time-exceptional"
        value="${res.cancellationExceptional.endTime}"
        class="form-control bg-aux"
        name="start-time"
    />`;
    document.getElementById('EnrollIn1S').innerHTML = `
    <input
        type="datetime-local"
        id="periodo1Inicio"
        value="${res.EnrollPeriod[1].start}"
        class="form-control bg-aux"
        name="start-time"
    />`;
    document.getElementById('EnrollIn1F').innerHTML = `
    <input
        type="datetime-local"
        id="periodo1Fin"
        value="${res.EnrollPeriod[1].end}"
        class="form-control bg-aux"
        name="start-time"
    />`;
    document.getElementById('EnrollIn2S').innerHTML = `
    <input
        type="datetime-local"
        id="periodo2Inicio"
        value="${res.EnrollPeriod[2].start}"
        class="form-control bg-aux"
        name="start-time"
    />`;
    document.getElementById('EnrollIn2F').innerHTML = `
    <input
        type="datetime-local"
        id="periodo2Fin"
        value="${res.EnrollPeriod[2].end}"
        class="form-control bg-aux"
        name="start-time"
    />`;
    document.getElementById('EnrollIn3S').innerHTML = `
    <input
        type="datetime-local"
        id="periodo3Inicio"
        value="${res.EnrollPeriod[3].start}"
        class="form-control bg-aux"
        name="start-time"
    />`;
    document.getElementById('EnrollIn3F').innerHTML = `
    <input
        type="datetime-local"
        id="periodo3Fin"
        value="${res.EnrollPeriod[3].end}"
        class="form-control bg-aux"
        name="start-time"
    />`;

    document.getElementById('start-time-UploadNotes').innerHTML = `
    <input
        type="datetime-local"
        id="start-time-uploadnotes"
        value="${res.uploadNotes.startTime}"
        class="form-control bg-aux"
        name="start-time"
    />`;

    document.getElementById('end-time-UploadNotes').innerHTML = `
    <input
        type="datetime-local"
        id="end-time-uploadnotes"
        value="${res.uploadNotes.endTime}"
        class="form-control bg-aux"
        name="start-time"
    />`;


    
    logs = res.logs.reverse();
    initGraph(logs);
    filteredLogs = [...logs]; 
    renderTable();
})

function initGraph(logs) {
    
    const roles = {
        0: "Administrador",
        1: "Admisiones",
        2: "Registro",
        3: "Docentes",
        4: "Docentes",
        5: "Docentes",
        7: "Estudiantes"
    };

    // Contadores de éxito y fallo por rol
    const stats = {
        'Administrador': { success: 0, failure: 0 },
        'Registro': { success: 0, failure: 0 },
        'Docentes': { success: 0, failure: 0 },
        'Admisiones': { success: 0, failure: 0 },
        'Estudiantes': { success: 0, failure: 0 }
    };

    // Procesar los logs
    logs.forEach(log => {
        const roleName = roles[log.role_id];
        if (roleName) {
            if (log.auth_status === 1) {
                stats[roleName].success++;
            } else {
                stats[roleName].failure++;
            }
        }
    });


    // Crear el gráfico
    const chart = document.getElementById('chart');
    

    // Generar las barras para cada rol
    for (const role in stats) {
        const roleStats = stats[role];
        const barContainer = document.createElement('div');
        barContainer.className = 'bar-container';

        // Barra de éxito (en porcentaje)
        const barSuccess = document.createElement('div');
        barSuccess.className = 'bar bar-success';
        const successHeight = (roleStats.success ) * 100;
        barSuccess.style.height = `${successHeight}%`;
        barSuccess.textContent = `${roleStats.success}`;

        // Barra de fallo (en porcentaje)
        const barFailure = document.createElement('div');
        barFailure.className = 'bar bar-failure';
        const failureHeight = (roleStats.failure ) * 100;
        barFailure.style.height = `${failureHeight}%`;
        barFailure.textContent = `${roleStats.failure}`;

        const label = document.createElement('div');
        label.className = 'label';
        label.textContent = role;

        barContainer.appendChild(barSuccess);
        barContainer.appendChild(barFailure);
        barContainer.appendChild(label);

        chart.appendChild(barContainer);
    }
}

function renderTable() {
    const tbody = document.getElementById('logTableBody');
    tbody.innerHTML = '';

    filteredLogs.slice((currentPage - 1) * rowsPerPage, currentPage * rowsPerPage).forEach(log => {
      const row = document.createElement('tr');
      if (log.auth_status === 1) {
        row.classList.add('table-success');
      } else {
        row.classList.add('table-danger'); 
      }
      row.innerHTML = `
        <td>${log.local_time}</td>
        <td>${log.ip_address}</td>
        <td>${log.auth_status === 1 ? 'Éxito' : 'Fallo'}</td>
        <td>${log.identifier}</td>
        <td>${log.type}</td>
      `;
      tbody.appendChild(row);
    });

    renderPagination();
  }

  function renderPagination() {
    const pagination = document.getElementById('pagination');
    const totalPages = Math.ceil(filteredLogs.length / rowsPerPage);
    pagination.innerHTML = '';

    for (let i = 1; i <= totalPages; i++) {
      const li = document.createElement('li');
      li.classList.add('page-item');
      li.classList.add('bg');
      const a = document.createElement('a');
      a.classList.add('page-link');
      a.classList.add('bg');
      a.href = '#';
      a.textContent = i;
      a.addEventListener('click', () => {
        currentPage = i;
        renderTable();
      });
      li.appendChild(a);
      pagination.appendChild(li);
    }
  }

function filterLogs() {
    const authStatusFilter = document.getElementById('authStatusFilter').value;
    const roleFilter = document.getElementById('roleFilter').value;

    filteredLogs = logs.filter(log => {
        return (authStatusFilter === '' || log.auth_status == authStatusFilter) &&
                (roleFilter === '' || log.role_id == roleFilter);
    });

    currentPage = 1;
    renderTable();
}

document.getElementById('authStatusFilter').addEventListener('change', filterLogs);
document.getElementById('roleFilter').addEventListener('change', filterLogs);
document.getElementById('rowsPerPage').addEventListener('change', (e) => {
rowsPerPage = parseInt(e.target.value);
renderTable();
});


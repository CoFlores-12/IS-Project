const filterCareer = document.getElementById('filterCareer');
const filterExam = document.getElementById('filterExam');
const aspTableBody = document.getElementById('aspTableBody');
const totalValidators = document.getElementById('totalValidators');
const applicantsxValidatorBtn = document.getElementById('applicantsxValidatorBtn');
const applicantsxValidator = document.getElementById('applicantsxValidator');
let toast = document.getElementById('toast');
let toastBody = document.getElementById('toastBody');
let toastTitle = document.getElementById('toastTitle');
const newUserModal = document.getElementById('newUserModal');
const newUserModalBS = new bootstrap.Modal(newUserModal)
let toastBS = new bootstrap.Toast(toast);
const Careers = [];
const Exams = [];


applicantsxValidatorBtn.addEventListener('click', (e)=>{
    applicantsxValidatorBtn.disabled = true;
    applicantsxValidatorBtn.innerHTML = `<div class="spinner-border text-secondary" role="status">
<span class="visually-hidden">Loading...</span>
</div>`;
fetch("/api/post/admin/assignValidator.php", {
    method: "POST"
})
.then((res)=>{
    return res.json()
})
.then((res)=>{
    applicantsxValidatorBtn.disabled = false;
    applicantsxValidatorBtn.innerHTML = 'Asignar aspirantes a validadores'
    if (res.status) {
        let messageContent = '<ul>';
        for (const applicantId in res.message) {
            messageContent += `<li>Validador ID ${applicantId}: ${res.message[applicantId]} asignaciones</li>`;
        }
        messageContent += '</ul>';
        

        toastTitle.innerHTML ='Sorteo exitoso'
        toastBody.innerHTML = `<div class="alert alert-success mb-0" role="alert">
        ${messageContent}
        </div>`
        toastBS.show();
    }else {
        
        toastTitle.innerHTML ='error al crear usuario'
        toastBody.innerHTML = `<div class="alert alert-danger mb-0" role="alert">
            ${res.message}
        </div>`
        toastBS.show();
    }
})
.catch(erro =>{
    applicantsxValidatorBtn.disabled = false;
    applicantsxValidatorBtn.innerHTML = 'Asignar aspirantes a validadores'
    toastTitle.innerHTML ='error al crear usuario'
    toastBody.innerHTML = `<div class="alert alert-danger mb-0" role="alert">
        error en servidor
    </div>`
    toastBS.show();
    console.log(erro);
    
})

})

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
        fetch("/api/post/admin/addUserValidator.php", {
            method: "POST",
            body: formData
        })
        .then((res)=>{
            return res.json()
        })
        .then((res)=>{
            createUserBtn.disabled = false;
            createUserBtn.innerHTML = 'Crear Usuario'
            if (res.status) {
                

                toastTitle.innerHTML ='Usuario validador creado exitoso'
                toastBody.innerHTML = `<div class="alert alert-success mb-0" role="alert">
                ${res.message}
                </div>`
                toastBS.show();
                const form = document.getElementById('newUSerForm');
                form.reset();
                form.classList.remove("was-validated");
                newUserModalBS.hide();
                totalValidators.innerHTML = parseInt(totalValidators.innerHTML)+1;
            }else {
                
                toastTitle.innerHTML ='error al crear usuario'
                toastBody.innerHTML = `<div class="alert alert-danger mb-0" role="alert">
                    ${res.message}
                </div>`
                toastBS.show();
            }
        })
        .catch(erro =>{
            toastTitle.innerHTML ='error al crear usuario'
            toastBody.innerHTML = `<div class="alert alert-danger mb-0" role="alert">
                error en servidor
            </div>`
            toastBS.show();
            console.log(erro);
            
            createUserBtn.disabled = false;
            createUserBtn.innerHTML = 'Crear Usuario'
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

document.querySelector('#cardApplicant').addEventListener('click', function () {
    var modal = new bootstrap.Modal(document.getElementById('applicantModal'));
    modal.show();
    aspTableBody.innerHTML = `<center><div class="spinner-border text-secondary" role="status">
<span class="visually-hidden">Loading...</span>
</div></center>`;

    fetch('/api/get/admin/applicants.php')
    .then((response) => response.json())
    .then((response) => {
        // Preparar datos
        const applicants = response.Asp.reverse();
        const examsForApplicants = response.Exams;
        let currentPage = 1;
        let rowsPerPage = parseInt(document.getElementById('rowsPerPageSelect').value);


        // Función para generar las filas
        function generateRows(page) {
            const startIndex = (page - 1) * rowsPerPage;
            const endIndex = startIndex + rowsPerPage;
            const currentApplicants = applicants.slice(startIndex, endIndex);

            aspTableBody.innerHTML = ""; // Limpiar tabla

            currentApplicants.forEach(element => {
                const examsForApplicant = examsForApplicants.filter(exam =>
                    exam.career_id === element.preferend_career_id ||
                    exam.career_id === element.secondary_career_id
                );

                const examCodes = examsForApplicant.map(exam => exam.exam_code).join(',');

                aspTableBody.innerHTML += `
                    <tr>
                        <td>${element.identity}</td>
                        <td>${element.full_name}</td>
                        <td>${element.preferend_career_name}</td>
                        <td>${element.secondary_career_name}</td>
                        <td>${examCodes}</td>
                    </tr>
                `;
            });
        }

        // Función para generar la paginación
        function generatePagination() {
            const totalPages = Math.ceil(applicants.length / rowsPerPage);
            const paginationElement = document.getElementById('pagination');
            paginationElement.innerHTML = ""; // Limpiar paginación

            // Crear botones de paginación
            for (let i = 1; i <= totalPages; i++) {
                const pageItem = document.createElement('li');
                pageItem.classList.add('page-item');
                pageItem.innerHTML = `<a class="page-link" href="#">${i}</a>`;

                pageItem.addEventListener('click', function () {
                    currentPage = i;
                    generateRows(currentPage); // Regenerar filas de acuerdo a la página
                    highlightPage(i); // Resaltar la página activa
                });

                paginationElement.appendChild(pageItem);
            }
        }

        // Resaltar la página activa
        function highlightPage(page) {
            const pageItems = document.querySelectorAll('.pagination .page-item');
            pageItems.forEach(item => item.classList.remove('active'));
            pageItems[page - 1].classList.add('active');
        }

        document.getElementById('rowsPerPageSelect').addEventListener('change', function () {
            rowsPerPage = parseInt(this.value); // Actualizar rowsPerPage
            currentPage = 1; // Reiniciar a la primera página
            generatePagination(); // Regenerar paginación
            generateRows(currentPage); // Regenerar filas
        });

        // Inicializar paginación y cargar las primeras filas
        generatePagination();
        generateRows(currentPage);
    });
});


document.getElementById('filterExam').addEventListener('change', filterByExam);
document.getElementById('filterCareer').addEventListener('change', filterByCareer);

function filterByExam() {
    const selectedExam = document.getElementById('filterExam').value.trim();
    const rows = document.querySelectorAll('#aspTableBody tr');
    
    rows.forEach(row => {
        if (selectedExam == 'Exams') {
            row.style.display = ''; 
            return;
        }
        const examCell = row.cells[4].textContent.trim(); 
        
        if (!selectedExam || examCell.includes(selectedExam)) {
            row.style.display = ''; 
        } else {
            row.style.display = 'none';
        }
    });
    filterByCareer
}

function filterByCareer() {
    const selectedCareer = document.getElementById('filterCareer').value.trim();
    const rows = document.querySelectorAll('#aspTableBody tr');
    
    rows.forEach(row => {
        if (selectedCareer == 'Career') {
            row.style.display = '';
            return;
        }
        const preferedCareer = row.cells[2].textContent.trim(); 
        const secondaryCareer = row.cells[3].textContent.trim(); 
        
        if (!selectedCareer || 
            preferedCareer.includes(selectedCareer) || 
            secondaryCareer.includes(selectedCareer)) {
            row.style.display = '';
        } else {
            row.style.display = 'none'; 
        }
    });
}


        document.querySelector('#addExamnBtn').addEventListener('click', function () {
            var modalAddExam = new bootstrap.Modal(document.getElementById('addExamModal'));
            modalAddExam.show();
            fetch('/api/get/public/examsAndCareers.php')
            .then((response)=>{return response.json()})
            .then((response)=>{
                
                const addExamnModalBody = document.getElementById('addExamnModalBody');
                const examnsToCareers = document.getElementById('examnsToCareers');

                let HTML = `<input type="text" list="examnsToCareers" class="form-control" placeholder="Examn">
        <select name="" id="CareerNewExamn" class="form-control my-4">
            <option value="">Select Career...</option>`;
                response['Careers'].forEach(career => {
                    HTML += `<option value="${career.career_id}">${career.career_name}</option>`
                });
                examnsToCareers.innerHTML = '';
                response['Exams'].forEach(exam => {
                    examnsToCareers.innerHTML+= `<option>${exam.exam_code}</option>`
                });
                HTML += ` </select><input class="form-control my-4" type="number" name="" id="passingScore" placeholder="passing score">
        <button id="addExamnBtn" class="w-full btn bg-custom-primary text-white">Add Exam</button>`;
                addExamnModalBody.innerHTML = HTML;

                document.getElementById('addExamnBtn').addEventListener('click', (e)=>{
                    e.target.innerHTML = '<div class="spinner-grow text" role="status"></div>';
                    e.target.disabled = true;

                    const exam = document.querySelector('input[placeholder="Examn"]').value;
                    const careerId = document.getElementById('CareerNewExamn').value;
                    const passingScore = document.getElementById('passingScore').value;

                    const formData = {
                        exam: exam,
                        career_id: careerId,
                        passing_score: passingScore
                    };

                    fetch('/api/post/admin/addExamToCareer.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(formData)
                    })
                    .then(response =>{
                        alert('Added!')
                        modalAddExam.hide();
                    }) 
                })
            })
        });

        
let btnSearchHistory = document.getElementById('btnSearchHistory');

const refreshIcon = document.getElementById('refreshIcon');
refreshChats.addEventListener('click', () => {
    refreshChats.classList.add('rotate');

    frameChats.contentWindow.location.reload();

    setTimeout(() => {
        refreshChats.classList.remove('rotate');
    }, 1000); 
});

let historyBody = document.getElementById('historyBody');
        let inputHistory = document.getElementById('inputHistory');
        btnSearchHistory.addEventListener('click', ()=>{
            if (inputHistory.value === '') {return; }
            historyBody.innerHTML = `<center><div class="spinner-grow text-secondary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>`

            fetch('/api/get/admin/studentHistory.php?student_identifier='+inputHistory.value)
            .then((response)=>{return response.json()})
            .then((response)=>{
                let table = `<table class="table bg-aux mt-2">
                                <thead>
                                    <tr class="bg-aux text">
                                    <th class="bg-aux text" scope="col">Code</th>
                                    <th class="bg-aux text" scope="col">Class</th>
                                    <th class="bg-aux text" scope="col">Score</th>
                                    <th class="bg-aux text" scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>`; 
                response.forEach(classHistory => {
                    table += `<tr class="bg-aux">
                            <th class="bg-aux text" scope="row">${classHistory['class_code']}</th>
                            <td class="bg-aux text">${classHistory['class_name']}</td>
                            <td class="bg-aux text">${classHistory['score']}</td>
                            <td class="bg-aux text">-</td>
                        </tr>`
                });

                table += `</tbody></table>`;
                historyBody.innerHTML = table
            })
            .catch(()=>{
                alert('Student not found')
            })
        })


        let btnSearcTeacher = document.getElementById('btnSearcTeacher');
        let resetBody = document.getElementById('resetBody');
        let inputTeacher = document.getElementById('inputTeacher');
        let btlChangePasswordModal = document.getElementById('changePassword');
        let alertSuccessEmail = document.getElementById('alertSuccessEmail');
        
        alertSuccessEmail.style.display = 'none';
    
        let changePassword = document.getElementById('changePassword');
        let changePasswordModal = new bootstrap.Modal(changePassword);

        btlChangePasswordModal.addEventListener("click", ()=>{
            changePasswordModal.show();
        })
        

        btnSearcTeacher.addEventListener('click', ()=>{
            if (inputTeacher.value === '') {return; }
            resetBody.innerHTML = `<center><div class="spinner-grow text-secondary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                   `;                     

            fetch('/api/get/admin/searchTeacher.php?teacher_identifier='+inputTeacher.value)
            .then((response)=>{return response.json()})
            .then((response)=>{
                let teacher; 

                if(response.status == 0){
                    teacher = ` 
                    <table class="bg-aux" border="0">
                    <tbody>
                    <tr>
                        <th>Nombre</th>
                        <th>${response.row.first_name}  ${response.row.last_name}</th>
                    </tr>
                    <tr>
                        <td>Telefono</td>
                        <td>${response.row.phone}</td>
                    </tr>
                     <tr>
                        <td>Correo personal(de recuperacion)</td>
                        <td><input class="bg" type="text" id="newEmail" placeholder="Email" value="${response.row.personal_email}"></td>
                    </tr>
                    </tbody>
                    </table>
                    <div class="d-grid gap-2 col-6 mx-auto mt-4">
                         <button class="btn btn-primary" id="btnChange" type="button" data-bs-target="#change" onclick="change(${response.row.employee_number})" >Enviar Correo</button>
                    </div>`;
                    resetBody.innerHTML = teacher
                    btnChange = document.getElementById("btnChange");

                    btnChange.addEventListener("click", ()=>{
                        btnChange.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Enviando...`;
                        btnChange.disabled = true;
                    }) 

                }
                if (response.status !== 0) {
                    teacher = ` 
                    <div class="alert alert-danger" role="alert">
                        Parametros Incorrectos
                    </div>`;
                        resetBody.innerHTML = teacher
                }
                
            })
            .catch(()=>{
                alert('Teacher not found')
            })
        })
        

        function change(ID) {
            const email = document.getElementById("newEmail").value;

           

            fetch('/api/post/admin/ResetPasswordTeacher.php', {
                method: 'POST', 
                headers: {
                    'Content-Type': 'application/json', 
                },
                body: JSON.stringify({ teacher_identifier: ID, personal_email: email}), 
            })
                .then(response => {
                    changePasswordModal.hide();
                    alertSuccessEmail.style.display = "block";
                    alertSuccessEmail.removeAttribute('hidden');
                    setTimeout(function() {
                        alertSuccessEmail.style.display = 'none';
                        resetBody.innerHTML = "";
                        inputTeacher.value = "";
                      }, 3000);

                })
                .catch(error => {
                    alert('Error, something went wrong');
                });
            
        }

        let btnNewClass = document.getElementById('newClass');
        let newClassBody = document.getElementById('newClassBody');
        let btnNewSection = document.getElementById('btnNewSection');
        let newSectionClass = document.getElementById('newSectionClass');

        let classes = document.getElementById('classes');
        let teachers = document.getElementById('teachers');
        let classrooms = document.getElementById('classrooms');
        let available_spaces = document.getElementById('available_spaces');
        let facultyID = document.getElementById('facultyID');
        
        let hourStart = document.getElementById('hourStart');
        let hourEnd = document.getElementById('hourEnd');
        let newSectionManualBtn = document.getElementById('newSectionManualBtn');
        let newSectionManual = document.getElementById('newSectionManual');
        let newSection = document.getElementById('newSection');
        let modalNewSection = new bootstrap.Modal(newSection);
        let modalNewSectionManual = new bootstrap.Modal(newSectionManual);

        let alertClassroom = document.getElementById('alertClassroom');
        let alertTeacher = document.getElementById('alertTeacher');
        let alertSuccess = document.getElementById('alertSuccess');
        let alertCapacity = document.getElementById('alertCapacity');
   
        let inlineCheckbox1 = document.getElementById('inlineCheckbox1');
        let inlineCheckbox2 = document.getElementById('inlineCheckbox2');
        let inlineCheckbox3 = document.getElementById('inlineCheckbox3');
        let inlineCheckbox4 = document.getElementById('inlineCheckbox4');
        let inlineCheckbox5 = document.getElementById('inlineCheckbox5');
        let inlineCheckbox6 = document.getElementById('inlineCheckbox6');

        let alertUploadFile = document.getElementById('alertUploadFile');
        alertUploadFile.style.display = 'none';
        
        newSectionClass.addEventListener('click', ()=>{
            modalNewSection.show();
        });
        newSectionManualBtn.addEventListener('click', (e)=>{
            e.target.innerHTML = `<div class="spinner-border" role="status">
                </div>`
            e.target.disabled = true;
            classes.value = '';
            teachers.value = ''
            classrooms.value =''
            
            available_spaces.value = ''

            fetch('/api/get/admin/searchFieldsDepartments.php')
            .then((response)=>{return response.json()})
            .then((data)=>{ 
                 
                 if (data.clases && Array.isArray(data.clases)) {
                    classes.innerHTML = '<option value="" selected>Select...</option>';
                    data.clases.forEach(clase => {
                        const option = document.createElement('option');
                        option.value = clase.class_id;
                        option.textContent = clase.class_name; 
                        classes.appendChild(option);
                    });
                } else {
                    console.error('That field was not found in the array');
                }

                if (data.teachers && Array.isArray(data.teachers)) {
                    teachers.innerHTML = '<option value="" selected>Select...</option>';
                    data.teachers.forEach(teacher => {
                        const option = document.createElement('option');
                        option.value = teacher.employee_number; 
                        option.textContent = teacher.first_name+" "+teacher.last_name; 
                        teachers.appendChild(option);
                    });
                } else {
                    console.error('That field was not found in the array');
                }
                 
                if (data.classroom && Array.isArray(data.classroom)) {
                    classrooms.innerHTML = '<option value="" selected>Select...</option>';
                    data.classroom.forEach(classroom => {
                        const option = document.createElement('option');
                        option.value = classroom.classroom_id; 
                        option.textContent = classroom.classroom_name+" / "+classroom.building_name+" / "+classroom.center_name; 
                        option.dataset.capacity = classroom.classroom_capacity;
                        classrooms.appendChild(option);
                    });
                } else {
                    console.error('That field was not found in the array');
                }

                classrooms.addEventListener('change', (event) => {
                    const selectedOption = event.target.selectedOptions[0];
                    const capacity = selectedOption ? selectedOption.dataset.capacity : ''; 
                
                    if (capacity) {
                        available_spaces.value = capacity; 
                    } else {
                        available_spaces.value = '';
                    }
                });

                modalNewSectionManual.show();
                e.target.innerHTML = `Manual`
                e.target.disabled = false;

            })
            .catch(()=>{
               alert('Teacher not found')
            })
        });

        

        hourStart.addEventListener('change', (e)=>{
            //TODO: validation
            let value = parseInt(e.target.value, 10);

            // Validar que la hora esté en el rango de 0700 a 1900
            if (value < 700 || value > 1900) {
                alert("La hora de inicio debe estar entre 0700 y 1900.");
                hourEnd.value = ""; // Limpiar hourEnd si está fuera del rango
                return;
            }
        
            // Formatear la entrada a 4 dígitos
            let startValue = value.toString().padStart(4, '0');
        
            // Calcular la hora de finalización sumándole 100 minutos
            let endValue = (value + 100).toString().padStart(4, '0');
        
            // Asignar los valores formateados
            hourStart.value = startValue;
            hourEnd.value = endValue;
        })

        alertClassroom.style.display = 'none';
        alertTeacher.style.display = 'none';

        alertSuccess.style.display = 'none';
        alertCapacity.style.display = 'none';

        

        btnNewSection.addEventListener('click', ()=>{

            alertClassroom.style.display = 'none';
            alertTeacher.style.display = 'none';
            alertSuccess.style.display = 'none';
            alertCapacity.style.display = 'none';

            let checkboxes = [
                inlineCheckbox1,
                inlineCheckbox2,
                inlineCheckbox3,
                inlineCheckbox4,
                inlineCheckbox5,
                inlineCheckbox6
            ];
    
            let selectedValues = checkboxes
                .filter(checkbox => checkbox.checked) 
                .map(checkbox => checkbox.value)    
                .join(',');       
            
            if (classes.value === '') { return; }
            if (teachers.value === '') { return; }
            if (available_spaces.value === '') { return; }
            if (hourStart.value === '') { return; }
            if (hourEnd.value === '') { return; }
            if (selectedValues === '') { return; }

            btnNewSection.disabled = true;
            btnNewSection.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Cargando...`;
            
            fetch('/api/post/admin/addSection.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    classId: classes.value,
                    starttime: hourStart.value,
                    endtime: hourEnd.value,
                    classroomId: classrooms.value,
                    teacherId: teachers.value,
                    quotas: available_spaces.value, 
                    days: selectedValues,
                    flag: "manual"
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status == 0) {
                    alertClassroom.style.display = 'block';
                    alertTeacher.style.display = 'block';
                    alertTeacher.removeAttribute('hidden');
                    alertClassroom.removeAttribute('hidden');
                } else if (data.status == 1) {
                    alertClassroom.style.display = 'block';
                    alertClassroom.removeAttribute('hidden');
                }else if (data.status == 2) {
                    alertTeacher.style.display = 'block';
                    alertTeacher.removeAttribute('hidden');
                } 
                else if (data.status == 3) {
                    alertCapacity.style.display = 'block';
                    alertCapacity.removeAttribute('hidden');
                }else  {
                    modalNewSection.hide();
                    modalNewSectionManual.hide();

                    inlineCheckbox1.checked = false;
                    inlineCheckbox2.checked = false;
                    inlineCheckbox3.checked = false;
                    inlineCheckbox4.checked = false;
                    inlineCheckbox5.checked = false;
                    inlineCheckbox6.checked = false;

                    hourStart.value = ""; 
                    hourEnd.value = ""; 

                    alertSuccess.style.display = 'block';
                    alertSuccess.removeAttribute('hidden');
                    setTimeout(function() {
                        alertSuccess.style.display = 'none';
                      }, 3000); 
                    
                }
                btnNewSection.disabled = false;
                btnNewSection.innerHTML = `Success`;
            })
            .catch(error => {
                
                console.error('Error:', error)}
            );
        })
        
        document.getElementById('searchWaitlistBtn').addEventListener('click', async () => {
            const classCode = document.getElementById('classCodeInput').value.trim();
        
            if (!classCode) {
                alert('Please enter a class code.');
                return;
            }
        
            try {
                const response = await fetch('/api/get/admin/searchWaitlist.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ class_code: classCode }),
                });
        
                const result = await response.json();
        
                if (result.error) {
                    alert(result.error);
                    return;
                }
        
                // Generar la tabla con los resultados
                displayWaitlistResults(result.data);
            } catch (error) {
                console.error('Error fetching waitlist data:', error);
                alert('An error occurred while fetching waitlist data.');
            }
        });
        
        function displayWaitlistResults(data) {
            let tableHtml = `
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Class Code</th>
                            <th>Waitlist ID</th>
                            <th>Section ID</th>
                            <th>Hour Start</th>
                            <th>Hour End</th>
                            <th>Student Count</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
        
            data.forEach(row => {
                tableHtml += `
                    <tr>
                        <td>${row.class_code}</td>
                        <td>${row.waitlist_id}</td>
                        <td>${row.section_id}</td>
                        <td>${row.hour_start}</td>
                        <td>${row.hour_end}</td>
                        <td>${row.student_count}</td>
                    </tr>
                `;
            });
        
            tableHtml += '</tbody></table>';
        
            // Mostrar los resultados en una nueva modal
            document.getElementById('resultsModalBody').innerHTML = tableHtml;
            const resultsModal = new bootstrap.Modal(document.getElementById('resultsModal'));
            resultsModal.show();
        }
        


    let uploadFile = document.getElementById('uploadFile');
    let csvFile = document.getElementById('csvFile');
        
           
    var table = document.getElementById('table');
    table.style.display = 'none'; 

    uploadFile.addEventListener("click", () => {
        const file = csvFile.files[0];
        alertUploadFile.style.display = 'none';
        if (!file) {
            alertUploadFile.style.display = 'block';
            alertUploadFile.removeAttribute('hidden');
            return;
        }

        table.innerHTML = "";

        uploadFile.disabled = true;
        uploadFile.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Cargando...`;

        const reader = new FileReader();

        reader.onload = function(e) {
            const csvContent = e.target.result;

            fetch('/api/post/admin/addSection.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    csvData: csvContent,
                    flag: "archive"
                })
            })
            .then(response => response.json())
            .then(result => {
                table.style.display = 'block'; 

                
                for (let key in result) {
                    if (result.hasOwnProperty(key)) { 
                        const item = result[key];
                        console.log(`Line ${item.line}, Status: ${item.status.status}`);

                        switch(item.status.status) {
                            case 0:
                                message = `Docente y aula ocupado a esta hora.`;
                                className = "alert table-danger";
                                break;
                            case 1:
                                message = `El aula esta ocupada a esta hora.`;
                                className = "alert alert-danger";
                                break;
                            case 2:
                                message = `El docente esta ocupado a esta hora`;
                                className = "alert alert-danger"; 
                                break;
                            case 3:
                                message = `Incorrecta la capacidad de estudiantes`;
                                className = "alert alert-danger"; 
                                break;
                            case "success":
                                message = `Seccion guardada`;
                                className = "table-success"; 
                                break;
                            default:
                                message = `Datos incorrectos.`;
                                className = "alert alert-danger"; 
                                break;
                        }

                        
                       let newRow = `
                        <tr class="${className}">
                            <th>${item.line}</th>
                            <td>${message}</td>
                        </tr>
                        `;

                        table.innerHTML += newRow
                    }
                }

               

            
                uploadFile.disabled = false;
                uploadFile.innerHTML = `Cargar Archivo`;
                
            })
            .catch(error => console.error('Error:', error));
        };

        reader.readAsText(file); 
    });


let btnSearcSection = document.getElementById('btnSearcSection');
let deleteSection = document.getElementById('deleteSection');

let alertIdsection = document.getElementById('alertIdsection');
alertIdsection.style.display = 'none';

let validedQuotas = document.getElementById('validedQuotas');
validedQuotas.style.display = 'none';

let invalidQuotas = document.getElementById('invalidQuotas');
invalidQuotas.style.display = 'none';

var tableDeleteSection = document.getElementById('tableDeleteSection');
const tableSection = tableDeleteSection.querySelector("tbody");
        
sections = [];

function showSection(){
        fetch('/api/get/admin/searchSections.php')
        .then((res) => {return res.json()})
        .then((res) =>{
            let newRow ="";   
            tableSection.innerHTML = "";
            sections = res.dataSection;
        
            res.dataSection.forEach((item) => {
    
                newRow = `
                <tr>
                    <th style="text-align: center;" class="bg-aux text">${item.section_id}</th>
                    <th style="text-align: center;" class="bg-aux text">${item.hour_start}</th>
                    <th style="text-align: center;" class="bg-aux text">${item.hour_end}</th>
                    <th style="text-align: center;" class="bg-aux text"> ${[
                    item.Monday && "Lunes",
                    item.Tuesday && "Martes",
                    item.Wednesday && "Miercoles",
                    item.Thursday && "Jueves",
                    item.Friday && "Viernes",
                    item.Saturday && "Sabado",
                ]
                    .filter(Boolean)
                    .join(", ")}</th>
                    <th style="text-align: center;" class="bg-aux text">${item.classroom_name}</th>
                    <th style="text-align: center;" class="bg-aux text">${item.enrolled_students}</th>
                    <th class="bg-aux text"><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalDelete" id="delete" onclick="modalVerifyDelete(${item.section_id})"><i class="bi bi-pencil-square"></i></button></th>
                </tr>
                `;
    
                tableSection.innerHTML += newRow
            })
    
            
                    
        });
}




let alertDelete = document.getElementById('alertDelete');
var tableSecction = document.getElementById('tableSecction');
const tableSectiondelete = tableSecction.querySelector("tbody");

let updateQuotas = document.getElementById('updateQuotas');


let justificationInput = document.getElementById('justificationInput');

let saveDeleteSection = document.getElementById('saveDeleteSection');

alertDelete.style.display = 'none';

var idSectionDelete = null

let modalDelete = document.getElementById('modalDelete');
let modalDeleteleSection = new bootstrap.Modal(modalDelete);

function modalVerifyDelete(id){
    modalDeleteleSection.show();
    const foundItem = sections.find(item => item.section_id === id);
    idSectionDelete = id;
    tableSectiondelete.innerHTML = "";

      const row1 = `
            <tr>
                <td class="bg-aux text">Seccion ID</td>
                <td class="bg-aux text">${foundItem.section_id}</td>
            </tr>
            `;
            const row2 = `
            <tr>
                <td class="bg-aux text">Clase</td>
                <td class="bg-aux text">${foundItem.class_name}</td>
            </tr>
            `;
            const row3 = `
            <tr>
                <td class="bg-aux text">Edificio</td>
                <td class="bg-aux text">${foundItem.building_name}</td>
            </tr>
            `;
            const row4 = `
            <tr>
                <td class="bg-aux text">Hora inicio</td>
                <td class="bg-aux text">${foundItem.hour_start}</td>
            </tr>
            `;
            const row5 = `
            <tr>
                <td class="bg-aux text">Hora fin</td>
                <td class="bg-aux text">${foundItem.hour_end}</td>
            </tr>
            `;
            const row6 = `
            <tr>
                <td class="bg-aux text">Periodo</td>
                <td class="bg-aux text">${foundItem.period_id}</td>
            </tr>
            `;
            const row7 = `
            <tr>
                <td class="bg-aux text">Aula ID</td>
                <td class="bg-aux text">${foundItem.classroom_id}</td>
            </tr>
            `;

            const row8 = `
            <tr>
                <td class="bg-aux text">Aula Nombre</td>
                <td class="bg-aux text">${foundItem.classroom_name}</td>
            </tr>
            `;

            const row9 = `
            <tr>
                <td class="bg-aux text">Cupos</td>
                <td class="bg-aux text"><input type="number" id="newQuotas" style="width: 100%; border: 2px solid red; border-radius: 4px; padding: 2px; box-sizing: border-box; background-color: #fff;" value="${foundItem.quotas}"></td>
            </tr>
            `;
            const row10 = `
            <tr>
                <td class="bg-aux text">Matriculados</td>
                <td class="bg-aux text">${foundItem.enrolled_students}</td>
            </tr>
            `;
      const days = [
        foundItem.Monday ? "Lunes" : "",
        foundItem.Tuesday ? "Martes" : "",
        foundItem.Wednesday ? "Miercoles" : "",
        foundItem.Thursday ? "Jueves" : "",
        foundItem.Friday ? "Viernes" : "",
        foundItem.Saturday ? "Sabado" : ""
      ]
        .filter(Boolean)
        .join(", ");

        tableSectiondelete.innerHTML = row1 + row2 + row3 + row4 + row5 + row6 + row7 + row8 + row9 + row10;
  
        tableSectiondelete.innerHTML += `
                                    <tr>
                                        <td  class="bg-aux text">Dias</td>
                                        <td  class="bg-aux text">${days}</td>
                                    </tr> `  

    tableSecctionStudentFuntion(foundItem.section_id);

    updateQuotas.addEventListener("click", ()=>{
        invalidQuotas.style.display = 'none';
        validedQuotas.style.display = 'none';
        let newQuotas = document.getElementById('newQuotas');
 
        if(newQuotas.value == ""){
            invalidQuotas.style.display = 'block';
            invalidQuotas.removeAttribute('hidden');
        }else{
            updateQuotas.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Cargando...`;
            updateQuotas.disabled = true;
            fetch('/api/put/admin/updateSectionQuotas.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    section_id: foundItem.section_id, 
                    new_quotas: newQuotas.value    
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 200) {
                        validedQuotas.style.display = 'block';
                        validedQuotas.removeAttribute('hidden');
                        setTimeout(function() {
                            validedQuotas.style.display = 'none';;
                          }, 3000); 
                        
                    } else {
                        invalidQuotas.style.display = 'block';
                        invalidQuotas.removeAttribute('hidden');
                        setTimeout(function() {
                            invalidQuotas.style.display = 'none';;
                          }, 3000); 
                    }
                    updateQuotas.innerHTML = `Actualizar Cupos`;
                    updateQuotas.disabled = false;
                })
                .catch(error => console.error('Error:', error));            
        }


    }) 
}

let tableSecctionStudent = document.getElementById("tableSecctionStudent");
let tableSectiondeleteStudent = tableSecctionStudent.querySelector("tbody");

function tableSecctionStudentFuntion(idSection){
    fetch('/api/get/admin/countClassesStudent.php?section_identifier='+idSection)
    .then((res) => {return res.json()})
    .then((res) =>{
    

        tableSectiondeleteStudent.innerHTML = `
                <thead>
                    <tr>
                    <th style="text-align: center;" scope="col" class="bg-aux text">Id del estudiante</th>
                    <th style="text-align: center;" scope="col" class="bg-aux text">Clases restantes</th>
                    <th style="text-align: center;" scope="col" class="bg-aux text">Clases aprobadas</th>
                    <th style="text-align: center;" scope="col" class="bg-aux text">Menos de 5 clases?</th>
                    </tr>
                </thead>
        `;

        console.log(res.rows)
    
        if(res.status == 0){
            res.rows.forEach((item) => {
                if(item.has_less_than_5_remaining == 1){
                    clase = "SI"
                }else{
                     clase = "NO"
                }
    
                newRow = `
                <tr>
                    <th style="text-align: center;" class="bg-aux text">${item.student_id}</th>
                    <th style="text-align: center;" class="bg-aux text">${item.pending_classes}</th>
                    <th style="text-align: center;" class="bg-aux text">${item.approved_classes}</th>
                    <th style="text-align: center;" class="bg-aux text">${clase}</th>
                   </tr>
                `;

                if (item.has_less_than_5_remaining === 1) {
                    saveDeleteSection.disabled = true;
                }

                tableSectiondeleteStudent.innerHTML += newRow;
            })
        }
        

    })
}


function modalDeleteSection(item){
    fetch('/api/delete/admin/deleteSection.php', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id: item }) 
    })
    .then(response => response.json())
    .then(data => {
        
        modalDeleteleSection.hide();
        alertDelete.style.display = 'block';
        alertDelete.removeAttribute('hidden');
        saveDeleteSection.disabled = false;
        saveDeleteSection.innerHTML = `<i class="bi bi-trash"></i>`
        setTimeout(function() {
            alertDelete.style.display = 'none';
          }, 3000);  
    })
    .catch(error => {
      console.log(error)
    });
}

function insertSectionDelete(sectionId, reason){
    fetch("/api/post/admin/sectionCancelled.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            section_id: sectionId,
            reason: reason,
        })
    })
        .then(response => response.json())
        .then(data => {
           
        })
        .catch(error => console.error("Error:", error));
}

deleteSection.addEventListener("click", ()=>{
    showSection();
});

        
btnSearcSection.addEventListener("click", () => {
    const inputSection = document.getElementById("inputSection").value.trim();
    alertIdsection.style.display = 'none';

    let found = false;
    if (inputSection === "") {
        Array.from(tableSection.rows).forEach(row => {
            row.style.display = "";  
        });
    }else{
        Array.from(tableSection.rows).forEach(row => {
            row.style.display = "none";
    
            const sectionId = row.cells[0].textContent.trim(); 
    
            if (sectionId === inputSection) {
                row.style.display = ""; 
                found = true;
            }
        });
    }

    if (!found && inputSection !="") {
        alertIdsection.style.display = 'block';
    }

});

let alertJustication = document.getElementById('alertJustication');

alertJustication.style.display = "none"

saveDeleteSection.addEventListener("click", ()=>{
    let razon = justificationInput.value;
    alertJustication.style.display = "none"
    if(!razon){
        alertJustication.style.display = "block"
        return;
    }
    saveDeleteSection.disabled = true;
    saveDeleteSection.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Cargando...`;

    const foundItem = sections.find(item => item.section_id === idSectionDelete);

    insertSectionDelete(foundItem.section_id, razon)
    modalDeleteSection(foundItem.section_id)
})

const toggleAside = document.getElementById('toggleAside');
const desktopAside = document.getElementById('desktopAside');
const statsStudents = document.getElementById('statsStudents');
const statsEmployees = document.getElementById('statsEmployees');
const statsClasses = document.getElementById('statsClasses');

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

fetch('/api/get/admin/getStats.php')
.then(res=>{return res.json()})
.then(res=>{
    statsStudents.innerHTML = res.students
    statsEmployees.innerHTML = res.employees
    statsClasses.innerHTML = res.classes
    const tasaValues = Object.values(res.tasa).map(Number); 
    const tasaLabels = Object.keys(res.tasa); 
    const pieCtx = document.getElementById("pieChart").getContext("2d");
    new Chart(pieCtx, {
        type: "pie",
        data: {
            labels: tasaLabels,
            datasets: [
                {
                    data: tasaValues,
                    backgroundColor: ["#176b87", "#ff6384", "#FF8000", "#ffce56"], 
                },
            ],
        },
        options: {
            plugins: {
                legend: { position: "top" },
            },
        },
    });

    const lineCtx = document.getElementById("lineChart").getContext("2d");

    res.avg.reverse()
    new Chart(lineCtx, {
        type: "line",
        data: {
            labels: res.avg.map(item => item.period), 
            datasets: [
                {
                    label: "Promedio de Calificaciones",
                    data: res.avg.map(item => item.average_score),
                    borderColor: "#176b87",
                    fill: false,
                },
            ],
        },
        options: {
            plugins: {
                legend: { display: true },
            },
            scales: {
                x: {
                    title: { display: true, text: "Periodos" },
                },
                y: {
                    title: { display: true, text: "Promedio" },
                    min: 0,
                    max: 100,
                },
            },
        },
    });

})
.catch(err=>{
    
})

let showEnrolled = document.getElementById("showEnrolled");
let bodyShowEnrolled = document.getElementById("bodyShowEnrolled");

showEnrolled.addEventListener("click", ()=>{
    fetchStudents();
})

async function fetchStudents() {
    try {
        const response = await fetch(`/api/get/admin/showEnrolled.php?departmentId=1`);
        const students = await response.json();

        if (!Array.isArray(students)) {
            throw new Error(students.error || 'Error desconocido');
        }
        
        bodyShowEnrolled.innerHTML = '';


            let table = `<table class="table bg-aux mt-2">
            <thead>
                <tr class="bg-aux text">
                <th class="bg-aux text" scope="col">Cuenta</th>
                <th class="bg-aux text" scope="col">Nombre</th>
                <th class="bg-aux text" scope="col">Apellido</th>
                <th class="bg-aux text" scope="col">Clases</th>
                </tr>
            </thead>
            <tbody>`; 
            students.forEach(student => {
            table += `<tr class="bg-aux">
                    <th class="bg-aux text" scope="row">${student['Account Number']}</th>
                    <td class="bg-aux text">${student['First Name']}</td>
                    <td class="bg-aux text">${student['Last Name']}</td>
                    <td class="bg-aux text">${student['Enrolled Classes']}</td>
                </tr>`
            });

            table += `</tbody></table>`;
            bodyShowEnrolled.innerHTML = table




    } catch (error) {
        console.error('Error fetching students:', error);
        alert('Error al obtener los datos. Revisa la consola para más detalles.');
    }
}


let btnassessment = document.getElementById("btnassessment");
let bodyEvaluations = document.getElementById("bodyEvaluations");

btnassessment.addEventListener("click", ()=>{
    fetch('/api/get/admin/teacherEvaluation.php')
    .then((response)=>{return response.json()})
    .then((evals) => {
        let table = `<table class="table bg-aux mt-2">
                        <thead>
                            <tr class="bg-aux text">
                                <th class="bg-aux text" scope="col">Num. Emple</th>
                                <th class="bg-aux text" scope="col">Nombre</th>
                                <th class="bg-aux text" scope="col">Sección</th>
                                <th class="bg-aux text" scope="col">Estudiante</th>
                                <th class="bg-aux text" scope="col">Evaluación</th>
                            </tr>
                        </thead>
                        <tbody>`; 
    
        evals.forEach((eval, index) => {
            const responses = JSON.parse(eval['responses'] || '{}');
            let scoreSum = 0; 
            let questionCount = 0;
    
            // Procesar respuestas
            for (const key in responses) {
                const value = responses[key]; 
                const numValue = parseFloat(value); 
    
                if (!isNaN(numValue) && numValue >= 0 && numValue <= 4) {
                    scoreSum += numValue; 
                    questionCount++;
                }
            }
    
            // Calcular el puntaje ajustado en base a 10
            const adjustedScore = (scoreSum / (questionCount * 4)) * 10;
    
            // Añadir fila a la tabla
            table += `<tr class="bg-aux" data-index="${index}">
                        <th class="bg-aux text" scope="row">${eval['employee_number']}</th>
                        <th class="bg-aux text" scope="row">${eval['teacher_name']}</th>
                        <th class="bg-aux text" scope="row">${eval['section_id']}</th>
                        <th class="bg-aux text" scope="row">${eval['student_account_number']}</th>
                        <th class="bg-aux text" scope="row">${adjustedScore.toFixed(2)}</th>
                    </tr>`;
        });
    
        table += `</tbody></table>`;
        bodyEvaluations.innerHTML = table;
    
        // Agregar el evento de clic a las filas
        const rows = document.querySelectorAll('tr[data-index]');
        rows.forEach(row => {
            row.addEventListener('click', () => {
                const index = row.getAttribute('data-index');
                const selectedEval = evals[index];
                const responses = JSON.parse(selectedEval['responses'] || '{}'); 
    
                // Mostrar detalles en el modal
                document.getElementById('modalTeacherName').innerText = selectedEval.teacher_name;
                document.getElementById('modalEmployessNumber').innerText = selectedEval.employee_number;
                document.getElementById('modalSection').innerText = selectedEval.section_id;
                document.getElementById('modalStudentNumber').innerText = selectedEval.student_account_number;
                document.getElementById('modalStudentQualification').innerText = selectedEval.student_score;
                
                
    
                // Mostrar las respuestas de las preguntas 1 y 2 como texto
                const questionMapping = ["Deficiente", "Malo", "Bueno", "Muy Bueno", "Excelente"];
                
                // Mostrar las respuestas de la pregunta 1 y 2 con las opciones correspondientes
                const question1Response = responses['question_1'] !== undefined ? questionMapping[parseInt(responses['question_1'])] : "Respuesta no disponible";
                const question2Response = responses['question_2'] !== undefined ? questionMapping[parseInt(responses['question_2'])] : "Respuesta no disponible";
                const question3Response = responses['question_3'] !== undefined ? questionMapping[parseInt(responses['question_3'])] : "Respuesta no disponible";
                const question4Response = responses['question_4'] !== undefined ? questionMapping[parseInt(responses['question_4'])] : "Respuesta no disponible";
                const question5Response = responses['question_5'] !== undefined ? questionMapping[parseInt(responses['question_5'])] : "Respuesta no disponible";
                const question6Response = responses['question_6'] !== undefined ? questionMapping[parseInt(responses['question_6'])] : "Respuesta no disponible";
                const question7Response = responses['question_7'] !== undefined ? questionMapping[parseInt(responses['question_7'])] : "Respuesta no disponible";
                const question8Response = responses['question_8'] !== undefined ? questionMapping[parseInt(responses['question_8'])] : "Respuesta no disponible";
                const question9Response = responses['question_9'] !== undefined ? questionMapping[parseInt(responses['question_9'])] : "Respuesta no disponible";
                const question10Response = responses['question_10'] !== undefined ? questionMapping[parseInt(responses['question_10'])] : "Respuesta no disponible";
                const question11Response = responses['question_11'] !== undefined ? questionMapping[parseInt(responses['question_11'])] : "Respuesta no disponible";
                const question12Response = responses['question_12'] !== undefined ? questionMapping[parseInt(responses['question_12'])] : "Respuesta no disponible";
                const question13Response = responses['question_13'] !== undefined ? questionMapping[parseInt(responses['question_13'])] : "Respuesta no disponible";
                const question14Response = responses['question_14'] !== undefined ? questionMapping[parseInt(responses['question_14'])] : "Respuesta no disponible";
                const question15Response = responses['question_15'] !== undefined ? questionMapping[parseInt(responses['question_15'])] : "Respuesta no disponible";
                const question16Response = responses['question_16'] !== undefined ? questionMapping[parseInt(responses['question_16'])] : "Respuesta no disponible";
                const question17Response = responses['question_17'] !== undefined ? questionMapping[parseInt(responses['question_17'])] : "Respuesta no disponible";
                const question18Response = responses['question_18'] !== undefined ? questionMapping[parseInt(responses['question_18'])] : "Respuesta no disponible";
                const question19Response = responses['question_19'] !== undefined ? questionMapping[parseInt(responses['question_19'])] : "Respuesta no disponible";
                const question20Response = responses['question_20'] !== undefined ? questionMapping[parseInt(responses['question_20'])] : "Respuesta no disponible";
                const question21Response = responses['question_21'] !== undefined ? questionMapping[parseInt(responses['question_21'])] : "Respuesta no disponible";
                const question22Response = responses['question_22'] !== undefined ? questionMapping[parseInt(responses['question_22'])] : "Respuesta no disponible";
                const question23Response = responses['question_23'] !== undefined ? questionMapping[parseInt(responses['question_23'])] : "Respuesta no disponible";
                const question24Response = responses['question_24'] !== undefined ? questionMapping[parseInt(responses['question_24'])] : "Respuesta no disponible";
                const question25Response = responses['question_25'] !== undefined ? questionMapping[parseInt(responses['question_25'])] : "Respuesta no disponible";
                const question26Response = responses['question_26'] !== undefined ? questionMapping[parseInt(responses['question_26'])] : "Respuesta no disponible";
                const question27Response = responses['question_27'] !== undefined ? questionMapping[parseInt(responses['question_27'])] : "Respuesta no disponible";
                const question28Response = responses['question_28'] !== undefined ? questionMapping[parseInt(responses['question_28'])] : "Respuesta no disponible";
                
                // Mostrar la respuesta de la pregunta 3 tal como está (texto libre)
                const question29Response = responses['justification_1'] || "Respuesta no disponible";
                const question30Response = responses['justification_2'] || "Respuesta no disponible";
                const question31Response = responses['justification_3'] || "Respuesta no disponible";
    
                // Desplegar las respuestas en el modal
                document.getElementById('modalResponses').innerHTML = `
                    <table class="table bg-aux mt-2">
                    <thead>
                        <tr>
                            <th class="bg-aux text" scope="col">#</th>
                            <th class="bg-aux text" scope="col">Pregunta</th>
                            <th class="bg-aux text" scope="col">Respuesta</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="bg-aux text">1</td>
                            <td class="bg-aux text">Al iniciar la clase ¿le facilitó por escrito el Programa de la asignatura, que contenía los objetivos de aprendizaje, temas, calendarización de clases y exámenes, formas y criterios de evaluación?</td>
                            <td class="bg-aux text"><strong>${question1Response}</strong></td>
                        </tr>
                        <tr>
                            <td class="bg-aux text">2</td>
                            <td class="bg-aux text">¿Demuestra estar actualizado y tener dominio de la disciplina que imparte?</td>
                            <td class="bg-aux text"><strong>${question2Response}</strong></td>
                        </tr>
                        <tr>
                            <td class="bg-aux text">3</td>
                            <td class="bg-aux text">¿Establece en la clase relación entre los contenidos teóricos y los prácticos?</td>
                            <td class="bg-aux text"><strong>${question3Response}</strong></td>
                        </tr>
                        <tr>
                            <td class="bg-aux text">4</td>
                            <td class="bg-aux text">¿Utiliza en el desarrollo del curso técnicas educativas que facilitan su aprendizaje (investigaciones en grupo, estudio de casos, visitas al campo, seminarios, mesas redondas, simulaciones, audiciones, ejercicio adicionales, sitios web, etc)?</td>
                            <td class="bg-aux text"><strong>${question4Response}</strong></td>
                        </tr>
                        <tr>
                            <td class="bg-aux text">5</td>
                            <td class="bg-aux text">¿Utiliza durante la clase medios audiovisuales que facilitan su aprendizaje?</td>
                            <td class="bg-aux text"><strong>${question5Response}</strong></td>
                        </tr>
                        <tr>
                            <td class="bg-aux text">6</td>
                            <td class="bg-aux text">¿Relaciona el contenido de esta asignatura con otras asignaturas que usted ya cursó?</td>
                            <td class="bg-aux text"><strong>${question6Response}</strong></td>
                        </tr>
                        <tr>
                            <td class="bg-aux text">7</td>
                            <td class="bg-aux text">Desarrolló contenidos adecuados en profundidad para el nivel que usted lleva en la carrera?</td>
                            <td class="bg-aux text"><strong>${question7Response}</strong></td>
                        </tr>
                        <tr>
                            <td class="bg-aux text">8</td>
                            <td class="bg-aux text">¿Selecciona temas y experiencias que le sean a Usted útiles en su vida profesional y cotidiana?</td>
                            <td class="bg-aux text"><strong>${question8Response}</strong></td>
                        </tr>
                        <tr>
                            <td class="bg-aux text">9</td>
                            <td class="bg-aux text">Además de las explicaciones, le recomendó en esta clase otras fuentes de consulta para el desarrollo de esta asignatura, accesibles a Usted, en cuanto a costo, ubicación, etc.?</td>
                            <td class="bg-aux text"><strong>${question9Response}</strong></td>
                        </tr>
                        <tr>
                            <td class="bg-aux text">10</td>
                            <td class="bg-aux text">¿Incentiva la participación de los estudiantes en la clase?</td>
                            <td class="bg-aux text"><strong>${question10Response}</strong></td>
                        </tr>
                        <tr>
                            <td class="bg-aux text">11</td>
                            <td class="bg-aux text">¿Asiste a las clases con puntualidad y según lo programado?</td>
                            <td class="bg-aux text"><strong>${question11Response}</strong></td>
                        </tr>
                        <tr>
                            <td class="bg-aux text">12</td>
                            <td class="bg-aux text">¿Inicia y finaliza las clases en el tiempo reglamentario?</td>
                            <td class="bg-aux text"><strong>${question12Response}</strong></td>
                        </tr>
                        <tr>
                            <td class="bg-aux text">13</td>
                            <td class="bg-aux text">¿Muestra interés en que usted aprenda?</td>
                            <td class="bg-aux text"><strong>${question13Response}</strong></td>
                        </tr>
                        <tr>
                            <td class="bg-aux text">14</td>
                            <td class="bg-aux text">¿Relaciona el contenido de la clase con la vida real?</td>
                            <td class="bg-aux text"><strong>${question14Response}</strong></td>
                        </tr>
                        <tr>
                            <td class="bg-aux text">15</td>
                            <td class="bg-aux text">¿Logra mantener la atención de los estudiantes durante el desarrollo de la clase?</td>
                            <td class="bg-aux text"><strong>${question15Response}</strong></td>
                        </tr>
                        <tr>
                            <td class="bg-aux text">16</td>
                            <td class="bg-aux text">¿Muestra buena disposición para aclarar y ampliar dudas sobre problemas que surgen durante las clases?</td>
                            <td class="bg-aux text"><strong>${question16Response}</strong></td>
                        </tr>
                        <tr>
                            <td class="bg-aux text">17</td>
                            <td class="bg-aux text">¿Trata respetuosamente, a los estudiantes, durante todos los momentos de la clase?</td>
                            <td class="bg-aux text"><strong>${question17Response}</strong></td>
                        </tr>
                        <tr>
                            <td class="bg-aux text">18</td>
                            <td class="bg-aux text">¿Mantiene un clima de cordialidad y respeto con todo el grupo de alumnos?</td>
                            <td class="bg-aux text"><strong>${question18Response}</strong></td>
                        </tr>
                        <tr>
                            <td class="bg-aux text">19</td>
                            <td class="bg-aux text">¿Brinda orientaciones o lineamientos claros sobre cómo hacer y presentar los trabajos asignados durante la clase?</td>
                            <td class="bg-aux text"><strong>${question19Response}</strong></td>
                        </tr>
                        <tr>
                            <td class="bg-aux text">20</td>
                            <td class="bg-aux text">¿Al inicio del periodo le explicó el sistema de evaluación a utilizarse durante el desarrollo del curso?</td>
                            <td class="bg-aux text"><strong>${question20Response}</strong></td>
                        </tr>
                        <tr>
                            <td class="bg-aux text">21</td>
                            <td class="bg-aux text">¿Practicó evaluaciones de acuerdo a los objetivos propuestos en las clases, los contenidos desarrollados y en las fechas previstas?</td>
                            <td class="bg-aux text"><strong>${question21Response}</strong></td>
                        </tr>
                        <tr>
                            <td class="bg-aux text">22</td>
                            <td class="bg-aux text">¿le entregó los resultados de las pruebas o exámenes y trabajos en el termino de 2 semanas. ?</td>
                            <td class="bg-aux text"><strong>${question22Response}</strong></td>
                        </tr>
                        <tr>
                            <td class="bg-aux text">23</td>
                            <td class="bg-aux text">¿En la revisión de las evaluaciones le permitió conocer sus aciertos y discutir sus equivocaciones?</td>
                            <td class="bg-aux text"><strong>${question23Response}</strong></td>
                        </tr>
                        <tr>
                            <td class="bg-aux text">24</td>
                            <td class="bg-aux text">¿Da a conocer criterios para calificar y los aplica al revisar los exámenes, prueba, trabajos?</td>
                            <td class="bg-aux text"><strong>${question24Response}</strong></td>
                        </tr>
                        <tr>
                            <td class="bg-aux text">25</td>
                            <td class="bg-aux text">¿Utiliza los exámenes y la revisión de estos, como medio para afianzar su aprendizaje?</td>
                            <td class="bg-aux text"><strong>${question25Response}</strong></td>
                        </tr>
                        <tr>
                            <td class="bg-aux text">26</td>
                            <td class="bg-aux text">¿Cuál fue su nivel de aprendizaje que tuvo, en esta asignatura?</td>
                            <td class="bg-aux text"><strong>${question26Response}</strong></td>
                        </tr>
                        <tr>
                            <td class="bg-aux text">27</td>
                            <td class="bg-aux text">¿Que grado de dificultad le asigna a los contenidos de esta asignatura?</td>
                            <td class="bg-aux text"><strong>${question27Response}</strong></td>
                        </tr>
                        <tr>
                            <td class="bg-aux text">28</td>
                            <td class="bg-aux text">¿En relación al número de alumnos que valor de la escala, asigna al ambiente académico (tamaño del aula, condiciones del mobiliario, condiciones acústicas)?</td>
                            <td class="bg-aux text"><strong>${question28Response}</strong></td>
                        </tr>
                        <tr>
                            <td>29</td>
                            <td>Qué cualidad docente identifica Usted en este profesor(a)?:</td>
                            <td><strong>${question29Response}</strong></td>
                        </tr>
                        <tr>
                            <td>30</td>
                            <td>A su criterio, ¿en que aspectos de su desempeño docente, su profesor puede mejorar?</td>
                            <td><strong>${question30Response}</strong></td>
                        </tr>
                        <tr>
                            <td>31</td>
                            <td>Ha identificado Usted en su profesor(a) una actitud no acorde con un docente universitario<</td>
                            <td><strong>${question31Response}</strong></td>
                        </tr>
                    </tbody>
                </table>
                `;
    
                // Mostrar el modal
                const modal = new bootstrap.Modal(document.getElementById('evaluationModal'));
                modal.show();
            });
        });
    })
    
    
    .catch(()=>{
        alert('Student not found')
    })       
})



let btnastatistics = document.getElementById("btnastatistics");
let bodyStatistics = document.getElementById("bodyStatistics");

btnastatistics.addEventListener("click", ()=>{
    fetch('/api/get/admin/teacherEvaluation.php')
    .then((response)=>{return response.json()})
    .then((evals) => {
        const surveyData = [];
        evals.forEach(response => {
            surveyData.push(response.responses)
        })

      const numQuestions = 28; 
      const scores = Array(numQuestions).fill(0);
      const responseCounts = Array(numQuestions).fill(0);
      const comments = [];
      
      surveyData.forEach(response => {
        const parsedResponse = JSON.parse(response);
        Object.keys(parsedResponse).forEach(key => {
          if (key.includes('question')) {
            const index = parseInt(key.split('_')[1]) - 1;
            scores[index] += parseInt(parsedResponse[key]);
            responseCounts[index]++;
          } else if (key.includes('justification')) {
            comments.push(parsedResponse[key]);
          }
        });
      });
      
      const averages = scores.map((score, index) => (score / responseCounts[index]).toFixed(2));
      
      const generalAverage = (scores.reduce((a, b) => a + b) / responseCounts.reduce((a, b) => a + b) * 2).toFixed(2);
      

    // Mostrar Promedio General
    document.getElementById('average').innerHTML = `Promedio General: ${generalAverage}`;

   

    // Crear gráfico de distribución de respuestas
    const distributionChartCanvas = document.getElementById('distributionChart');
const distributionChartCtx = distributionChartCanvas.getContext('2d');
const responseDistribution = Array(5).fill(0); // Respuestas de 0 a 4

// Recorrer las respuestas y contar la distribución
surveyData.forEach(response => {
  const parsedResponse = JSON.parse(response);
  Object.keys(parsedResponse).forEach(key => {
    if (key.includes('question')) {
      const rating = parseInt(parsedResponse[key]); // Obtener la calificación
      responseDistribution[rating]++; // Contar las respuestas en el rango 0-4
    }
  });
});

// Configuración de las barras del gráfico
const distributionBarWidth = 50;
const distributionBarSpacing = 10;
const distributionMax = Math.max(...responseDistribution); // Máximo número de respuestas
const distributionScale = distributionChartCanvas.height / distributionMax; // Escala para ajustar las barras

// Dibujar las barras de distribución
responseDistribution.forEach((count, index) => {
  const barHeight = count * distributionScale;
  const x = index * (distributionBarWidth + distributionBarSpacing) + 50; // Posición X
  const y = distributionChartCanvas.height - barHeight; // Posición Y (invertida, de abajo hacia arriba)

  // Dibujar la barra
  distributionChartCtx.fillStyle = '#ff6384'; // Color de las barras
  distributionChartCtx.fillRect(x, y, distributionBarWidth, barHeight);

  // Agregar texto con la distribución
  distributionChartCtx.fillStyle = '#000';
  distributionChartCtx.font = '14px Arial';
  distributionChartCtx.fillText(`Rating ${index + 1}: ${count}`, x, y - 5); // Mostrar Rating 1-5 y el conteo
});

    // Mostrar comentarios destacados
    const commentList = document.getElementById('commentList');
    commentList.innerHTML = "";
    comments.slice(0, 5).forEach(comment => {
      const listItem = document.createElement('li');
      listItem.textContent = comment;
      commentList.appendChild(listItem);
    });
    
    })
    .catch(()=>{
        
    })       
})


document.getElementById('showGrades').addEventListener('click', function () {
    loadGrades(1, 10); // Cargar la primera página con 10 items por defecto
});

function loadGrades(page, itemsPerPage) {
    fetch(`/api/get/admin/getActivePeriodGrades.php?page=${page}&itemsPerPage=${itemsPerPage}`)
        .then(response => response.json())
        .then(data => {
            renderTable(data.data);
            renderPagination(data.total, data.page, data.itemsPerPage);
        })
        .catch(error => console.error('Error:', error));
}

function renderTable(data) {
    const tableBody = document.getElementById('gradesModalBody');
    const rows = data.map(row => `
        <tr>
            <td>${row.class_code}</td>
            <td>${row.first_name} ${row.last_name}</td>
            <td>${row.student_id}</td>
            <td>${row.indicator} PAC ${row.year}</td>
            <td>${row.score}</td>
        </tr>
    `).join('');
    tableBody.innerHTML = `
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Docente</th>
                    <th>Num. Cuenta Estudiante</th>
                    <th>Periodo</th>
                    <th>Calificación</th>
                </tr>
            </thead>
            <tbody>
                ${rows}
            </tbody>
        </table>
    `;
}

function renderPagination(total, currentPage, itemsPerPage) {
    const totalPages = Math.ceil(total / itemsPerPage);
    let paginationHTML = '';

    for (let i = 1; i <= totalPages; i++) {
        paginationHTML += `
            <button class="btn btn-sm ${i === currentPage ? 'btn-primary' : 'btn-secondary'}" onclick="loadGrades(${i}, ${itemsPerPage})">
                ${i}
            </button>
        `;
    }

    const footer = `
        <div>
            <select id="itemsPerPage" onchange="loadGrades(1, this.value)">
                <option value="5" ${itemsPerPage == 5 ? 'selected' : ''}>5</option>
                <option value="10" ${itemsPerPage == 10 ? 'selected' : ''}>10</option>
                <option value="20" ${itemsPerPage == 20 ? 'selected' : ''}>20</option>
            </select>
            <span>Páginas totales: ${totalPages}</span>
        </div>
    `;

    document.getElementById('gradesModalBody').insertAdjacentHTML('beforeend', footer);
}


 

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
                    <table class="w-full mx-4" border="0">
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
                        <td><input type="text" id="newEmail" placeholder="Email" value="${response.row.personal_email}"></td>
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
                    console.log(data.message);
                } else if (data.status == 1) {
                    alertClassroom.style.display = 'block';
                    alertClassroom.removeAttribute('hidden');
                    console.log(data.message);
                }else if (data.status == 2) {
                    alertTeacher.style.display = 'block';
                    alertTeacher.removeAttribute('hidden');
                    console.log(data.message);
                } 
                else if (data.status == 3) {
                    alertCapacity.style.display = 'block';
                    alertCapacity.removeAttribute('hidden');
                    console.log(data.message);
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
        setTimeout(function() {
            alertDelete.style.display = 'none';
          }, 3000); 
       console.log(data)
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
    saveDeleteSection.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...`;

    const foundItem = sections.find(item => item.section_id === idSectionDelete);

    insertSectionDelete(foundItem.section_id, razon)
    modalDeleteSection(foundItem.section_id)
})



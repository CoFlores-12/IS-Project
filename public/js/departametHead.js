let btnSearchHistory = document.getElementById('btnSearchHistory');
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
                        <th>Indentity</th>
                        <th>${response.row.first_name}  ${response.row.last_name}</th>
                    </tr>
                    <tr>
                        <td>Phone</td>
                        <td>${response.row.phone}</td>
                      </tr>
                     <tr>
                        <td>Personal email</td>
                        <td><input type="text" id="newEmail" placeholder="Email" value="${response.row.personal_email}"></td>
                    </tr>
                    </tbody>
                    </table>
                    <div class="d-grid gap-2 col-6 mx-auto mt-4">
                    <button class="btn btn-primary" type="button" data-bs-target="#change" onclick="change(${response.row.employee_number})" >Change Password</button>
                    </div>`;
                    resetBody.innerHTML = teacher
                }
                if (response.status !== 0) {
                    teacher = ` 
                    <div class="alert alert-danger" role="alert">
                        Incorrect parameters
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
                    console.log(response)
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
    
            console.log(classes.value, "  - ", teachers.value, "  - ",classrooms.value, " - ",available_spaces.value, " - ", hourStart.value, " - ",hourEnd.value, " - ",selectedValues);

            btnNewSection.disabled = true;
            btnNewSection.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...`;
            
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
                    console.log(data.message);
                } else if (data.status == 1) {
                    alertClassroom.style.display = 'block';
                    console.log(data.message);
                }else if (data.status == 2) {
                    alertTeacher.style.display = 'block';
                    console.log(data.message);
                } 
                else if (data.status == 3) {
                    alertCapacity.style.display = 'block';
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
        


    let uploadFile = document.getElementById('uploadFile');
    let csvFile = document.getElementById('csvFile');
        
           
    var table = document.getElementById('table');
    table.style.display = 'none'; 

    uploadFile.addEventListener("click", () => {
        const file = csvFile.files[0];

        if (!file) {
            alert("Please select a file.");
            return;
        }

        table.innerHTML = "";

        uploadFile.disabled = true;
        uploadFile.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...`;

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
                                message = `Both the teacher and the classroom are available at this time.`;
                                className = "alert table-danger";
                                break;
                            case 1:
                                message = `The classroom is occupied at this time.`;
                                className = "alert alert-danger";
                                break;
                            case 2:
                                message = `The teacher is occupied at this time.`;
                                className = "alert alert-danger"; 
                                break;
                            case 3:
                                message = `Incorrect student capacity.`;
                                className = "alert alert-danger"; 
                                break;
                            case "success":
                                message = `Saved correctly.`;
                                className = "table-success"; 
                                break;
                            default:
                                message = `Data is missing.`;
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
                uploadFile.innerHTML = `Upload file`;
                
            })
            .catch(error => console.error('Error:', error));
        };

        reader.readAsText(file); 
    });



  
let btnSearcSection = document.getElementById('btnSearcSection');
let deleteSection = document.getElementById('deleteSection');

let alertIdsection = document.getElementById('alertIdsection');
alertIdsection.style.display = 'none';

var tableDeleteSection = document.getElementById('tableDeleteSection');
const tableSection = tableDeleteSection.querySelector("tbody");
        
sections = [];

function showSection(){
        fetch('/api/get/admin/searchSections.php')
        .then((res) => {return res.json()})
        .then((res) =>{
            let newRow ="";   
            tableSection.innerHTML = "";
            sections = res;
            res.forEach((item) => {
    
                newRow = `
                <tr>
                    <th>${item.section_id}</th>
                    <th>${item.hour_start}</th>
                    <th>${item.hour_end}</th>
                    <th> ${[
                    item.Monday && "Monday",
                    item.Tuesday && "Tuesday",
                    item.Wednesday && "Wednesday",
                    item.Thursday && "Thursday",
                    item.Friday && "Friday",
                    item.Saturday && "Saturday",
                ]
                    .filter(Boolean)
                    .join(", ")}</th>
                    <th>${item.classroom_name}</th>
                    <th>${item.enrolled_students}</th>
                    <th><button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalDelete" id="delete" onclick="modalVerifyDelete(${item.section_id})">Danger</button></th>
                </tr>
                `;
    
                tableSection.innerHTML += newRow
            });
            
                    
        });
}

let alertDelete = document.getElementById('alertDelete');
var tableSecction = document.getElementById('tableSecction');
const tableSectiondelete = tableSecction.querySelector("tbody");

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

    for (const [key, value] of Object.entries(foundItem)) {
        if (
          key !== "Monday" &&
          key !== "Tuesday" &&
          key !== "Wednesday" &&
          key !== "Thursday" &&
          key !== "Friday" &&
          key !== "Saturday"
        ) {
          const row = `
            <tr>
              <td>${key}</td>
              <td>${value}</td>
            </tr>
          `;
          tableSectiondelete.innerHTML += row;
        }
      }
  
      const days = [
        foundItem.Monday ? "Monday" : "",
        foundItem.Tuesday ? "Tuesday" : "",
        foundItem.Wednesday ? "Wednesday" : "",
        foundItem.Thursday ? "Thursday" : "",
        foundItem.Friday ? "Friday" : "",
        foundItem.Saturday ? "Saturday" : ""
      ]
        .filter(Boolean)
        .join(", ");
  
        tableSectiondelete.innerHTML += `
                                    <tr>
                                        <td>Days</td>
                                        <td>${days}</td>
                                    </tr>
                                `
            
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



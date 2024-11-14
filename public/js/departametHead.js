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

                let teacher = ` 
                    <table class="w-full mx-4" border="0">
                    <tbody>
                    <tr>
                        <th>Indentity</th>
                        <th>${response['first_name']}  ${response['last_name']}</th>
                    </tr>
                    <tr>
                        <td>Phone</td>
                        <td>${response['phone']}</td>
                      </tr>
                     <tr>
                        <td>Personal email</td>
                        <td><input type="text" id="newEmail" placeholder="Email" value="${response['personal_email']}"></td>
                    </tr>
                    </tbody>
                    </table>
                    <div class="d-grid gap-2 col-6 mx-auto mt-4">
                    <button class="btn btn-primary" type="button" data-bs-target="#change" onclick="change(${response['employee_number']})" >Change Password</button>
                    </div>`;
                
                resetBody.innerHTML = teacher;
            })
            .catch(()=>{
                alert('Teacher not found')
            })
        })

        function change(ID) {
            const email = document.getElementById("newEmail").value;

            console.log("hola", ID, email);
           fetch(`/api/put/teacher/ResetPasswordTeacher.php?teacher_identifier=${ID}&email=${email}`)
            .then((response)=>{return response.json()})
            .catch(()=>{
                alert('Error, something went wrong')
            })
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

        let inlineCheckbox1 = document.getElementById('inlineCheckbox1');
        let inlineCheckbox2 = document.getElementById('inlineCheckbox2');
        let inlineCheckbox3 = document.getElementById('inlineCheckbox3');
        let inlineCheckbox4 = document.getElementById('inlineCheckbox4');
        let inlineCheckbox5 = document.getElementById('inlineCheckbox5');
        let inlineCheckbox6 = document.getElementById('inlineCheckbox6');

        //TODO: create event to send file csv with section

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

        btnNewSection.addEventListener('click', ()=>{
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


            //TODO: validation inputs y create JSON with data
            
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
                    days: selectedValues
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                } else {
                    console.error(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        })
        

    
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




        /*lo nuevo */

        let btnNewClass = document.getElementById('newClass');
        let newClassBody = document.getElementById('newClassBody');
        let btnNewSection = document.getElementById('btnNewSection');
        let newSectionClass = document.getElementById('newSectionClass');

        let classes = document.getElementById('classes');
        let teachers = document.getElementById('teachers');
        let classrooms = document.getElementById('classrooms');
        let schedule = document.getElementById('schedule');
        let available_spaces = document.getElementById('available_spaces');
        let facultyID = document.getElementById('facultyID');
        
        

        newSectionClass.addEventListener('click', ()=>{
            fetch('/api/get/admin/searchFieldsDepartments.php')
            .then((response)=>{return response.json()})
            .then((data)=>{
                 console.log(data)  
                 
                 if (data.clases && Array.isArray(data.clases)) {
                    data.clases.forEach(clase => {
                        const option = document.createElement('option');
                        option.value = clase.class_id; // Valor de la opción
                        option.textContent = clase.class_name; // Texto visible de la opción
                        classes.appendChild(option);
                    });
                } else {
                    console.error('No se encontró la propiedad "classes" o no es un array válido');
                }

                if (data.teachers && Array.isArray(data.teachers)) {
                    data.teachers.forEach(teacher => {
                        const option = document.createElement('option');
                        option.value = teacher.employee_number; // Valor de la opción
                        option.textContent = teacher.first_name+" "+teacher.last_name; // Texto visible de la opción
                        teachers.appendChild(option);
                    });
                } else {
                    console.error('No se encontró la propiedad "classes" o no es un array válido');
                }
                 
                if (data.classroom && Array.isArray(data.classroom)) {
                    data.classroom.forEach(classroom => {
                        const option = document.createElement('option');
                        option.value = classroom.classroom_id; // Valor de la opción
                        option.textContent = classroom.classroom_name+" / "+classroom.building_name+" / "+classroom.center_name; // Texto visible de la opción
                        classrooms.appendChild(option);
                    });
                } else {
                    console.error('No se encontró la propiedad "classes" o no es un array válido');
                }

            })
            .catch(()=>{
               alert('Teacher not found')
            })
        })

        

    
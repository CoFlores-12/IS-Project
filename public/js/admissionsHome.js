const filterCareer = document.getElementById('filterCareer');
        const filterExam = document.getElementById('filterExam');
        const aspTableBody = document.getElementById('aspTableBody');
        const Careers = [];
        const Exams = [];
        document.querySelector('#cardApplicant').addEventListener('click', function () {
            var modal = new bootstrap.Modal(document.getElementById('applicantModal'));
            modal.show();
            aspTableBody.innerHTML = `<center><div class="spinner-border text-secondary" role="status">
  <span class="visually-hidden">Loading...</span>
</div></center>`
            fetch('/api/get/admin/applicants.php')
            .then((response)=>{return response.json()})
            .then((response)=>{
                console.log(response);
                
                aspTableBody.innerHTML = ``
                response.Asp.reverse().forEach(element => {
                    const examsForApplicant = response.Exams.filter(exam =>
                        exam.career_id === element.preferend_career_id ||
                        exam.career_id === element.secondary_career_id
                    );

                    const examCodes = examsForApplicant.map(exam => {
                        if (!Exams.includes(exam.exam_code)) {
                            Exams.push(exam.exam_code);
                        }
                        
                        return exam.exam_code
                    }).join(',');

                    if (!Careers.includes(element.preferend_career_name)) {
                        Careers.push(element.preferend_career_name)
                    }
                    if (!Careers.includes(element.secondary_career_name)) {
                        Careers.push(element.secondary_career_name)
                    }
                    aspTableBody.innerHTML += `<tr>
                                <td>${element.identity}</td>
                                <td>${element.full_name}</td>
                                <td>${element.preferend_career_name}</td>
                                <td>${element.secondary_career_name}</td>
                                <td>${examCodes}</td>
                            </tr>`
                });
                filterCareer.innerHTML = '<option>Career</option>'
                Careers.forEach(element => {
                    filterCareer.innerHTML += `<option>${element}</option>`
                });
                filterExam.innerHTML = '<option>Exams</option>'
                Exams.forEach(element => {
                    filterExam.innerHTML += `<option>${element}</option>`
                });
                
            })

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



        document.querySelector('#cardAdmitted').addEventListener('click', function () {
            var modal = new bootstrap.Modal(document.getElementById('admittedModal'));
            modal.show();
        });
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

        
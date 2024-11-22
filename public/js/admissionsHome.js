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

        
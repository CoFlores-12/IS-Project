document.addEventListener("DOMContentLoaded", () => {
    const employeeNumber = new URLSearchParams(window.location.search).get("employee_number");

    if (!employeeNumber) {
        alert("El número de empleado no se proporcionó en la URL.");
        return;
    }

    fetch(`/api/get/admin/getTeacherProfile.php?employee_number=${employeeNumber}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }

            // Insertar datos en el DOM
            document.getElementById("fullName").value = data.fullName || "";
            document.getElementById("personalEmail").value = data.personalEmail || "";
            document.getElementById("instituteEmail").value = data.instituteEmail || "";
            document.getElementById("phone").value = data.phone || "";
            document.getElementById("role").value = data.role || "";
            document.getElementById("departament").value = data.department || "";
        })
        .catch(error => console.error("Error al obtener los datos del perfil:", error));
});

    // Función para crear las tarjetas
    function createCard(sectionId, hourStart, classCode, className) {
        return `
            <div class="col-md-4 mb-3">
                <div class="card bg border-secondary">
                    <div class="card-body">
                        <h5 class="card-title text">${className} (${classCode})</h5>
                        <p class="card-text text">
                            <strong>Sección ID:</strong> ${sectionId}<br>
                            <strong>Hora de inicio:</strong> ${hourStart}
                        </p>
                    </div>
                </div>
            </div>
        `;
    }

    // Función para obtener parámetros de la URL
    function getEmployeeNumberFromURL() {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get('employee_number');
    }


    async function loadClassesHistory() {
        const historyContainer = document.getElementById('history');
        historyContainer.innerHTML = '<p>Cargando...</p>';
    
        // Obtener employee_number de la URL
        const employeeNumber = getEmployeeNumberFromURL();
    
        if (!employeeNumber) {
            historyContainer.innerHTML = '<p>Error: Número de empleado no definido en la URL.</p>';
            return;
        }
    
        try {
            const url = `/api/get/admin/classesHistoryByEmpId.php?employee_number=${employeeNumber}`;
            console.log('URL generada:', url);
    
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`Error al obtener los datos: ${response.statusText}`);
            }
            const data = await response.json();
    
            if (data.error) {
                historyContainer.innerHTML = `<p>Error: ${data.error}</p>`;
                return;
            }
    
            if (data.length === 0) {
                historyContainer.innerHTML = '<p>No hay clases asignadas en el historial.</p>';
                return;
            }
    
            const cards = data.map(item =>
                createCard(item.section_id, item.hour_start, item.class_code, item.class_name)
            ).join('');
            historyContainer.innerHTML = cards;
        } catch (error) {
            console.error('Error al cargar el historial de clases:', error);
            historyContainer.innerHTML = '<p>Error al cargar el historial de clases.</p>';
        }
    }    

    // Cargar el historial al cargar la página
    document.addEventListener('DOMContentLoaded', loadClassesHistory);
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

// Función para obtener parámetros de la URL
function getEmployeeNumberFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('employee_number');
}

// Obtener el número de empleado desde la URL
const employeeNumber = getEmployeeNumberFromURL();

// Verificar si employeeNumber está definido antes de cargar las clases
if (!employeeNumber) {
    console.error('El número de empleado no está presente en la URL');
    document.querySelector('#classesHistoryTable tbody').innerHTML = '<tr><td colspan="4">Error: Falta el número de empleado en la URL.</td></tr>';
} else {
    // Función para cargar el historial de clases
    async function loadClassesHistory() {
        const tableBody = document.querySelector('#classesHistoryTable tbody');
        tableBody.innerHTML = '<tr><td colspan="4">Cargando...</td></tr>';

        try {
            const response = await fetch(`/api/get/admin/classesHistoryByEmpId.php?employee_number=${employeeNumber}`);
            if (!response.ok) {
                throw new Error(`Error al obtener los datos: ${response.statusText}`);
            }
            const data = await response.json();

            // Verificar si hay datos
            if (data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="4">No hay clases asignadas en el historial.</td></tr>';
                return;
            }

            // Generar las filas de la tabla
            const rows = data.map(item => `
                <tr>
                    <td>${item.section_id}</td>
                    <td>${item.hour_start}</td>
                    <td>${item.class_code}</td>
                    <td>${item.class_name}</td>
                </tr>
            `).join('');

            // Colocar las filas en la tabla
            tableBody.innerHTML = rows;

            // Inicializar o recargar la paginación
            $('#classesHistoryTable').DataTable();
        } catch (error) {
            console.error('Error al cargar el historial de clases:', error);
            tableBody.innerHTML = '<tr><td colspan="4">Error al cargar el historial de clases.</td></tr>';
        }
    }

    // Cargar el historial al cargar la página
    document.addEventListener('DOMContentLoaded', loadClassesHistory);
}
let loadingModal = document.getElementById('loadingModal');
let MyModalLoading = new bootstrap.Modal(loadingModal);
let mainCareer = this.document.getElementById('mainCareer');
let secondaryCareer = this.document.getElementById('secondaryCareer');
let regional = document.getElementById('regionalCenter');
let toast = document.getElementById('toast');
let toastBody = document.getElementById('toastBody');
let toastTitle = document.getElementById('toastTitle');
let sendBtn = document.getElementById('sendBtn');
let toastBS = new bootstrap.Toast(toast);

document.getElementById("admissionForm").addEventListener("submit", async function (event) {
    event.preventDefault(); // Evita la recarga del formulario
    
    const form = event.target;
    const identityInput = document.getElementById('identity');
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

    // Recopilar los datos del formulario
    const formData = new FormData(form);
    sendBtn.disabled = true;
    try {
        fetch("/api/post/admissions/form.php", {
            method: "POST",
            body: formData
        })
        .then((res)=>{return res.json()})
        .then((res)=>{
            if (res.status) {
                sendBtn.disabled = false;
                toastTitle.innerHTML ='Proceso de admisión exitoso'
                toastBody.innerHTML = `<div class="alert alert-success mb-0" role="alert">
                ${res.message}
                </div>`
                toastBS.show();
                const form = document.getElementById('admissionForm');
                form.reset();
                form.classList.remove("was-validated");
            }else {
                sendBtn.disabled = false;
                toastTitle.innerHTML ='error en el proceso de admisión'
                toastBody.innerHTML = `<div class="alert alert-danger mb-0" role="alert">
                    ${res.message}
                </div>`
                toastBS.show();
                
            }
        })

    } catch (error) {
        console.error("Error:", error);
    }
});

document.getElementById('phone').addEventListener('input', function (e) {
    let input = e.target;
    let value = input.value.replace(/\D/g, '');
    if (value.length > 0 && !/[389]/.test(value[0])) {
        value = '';
    }

    let formattedValue = '';
    if (value.length > 0) {
        formattedValue = value.substring(0, 4);
    }
    if (value.length > 4) {
        formattedValue += '-' + value.substring(4, 8);
    }

    input.value = formattedValue;
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

    // Validar longitud
    if (cleanedId.length !== 13) return false;

    // Separar las secciones
    const departmentCode = cleanedId.substring(0, 2);
    const municipalityCode = cleanedId.substring(0, 4);
    const year = cleanedId.substring(4, 8);
    const correlativo = cleanedId.substring(8, 13);

    // Validar que el departamento exista
    if (!departments[departmentCode]) return false;

    // Validar que el municipio exista
    if (!departments[departmentCode].includes(municipalityCode)) return false;

    // Validar año de inscripción (1900-2024)
    const yearNumber = parseInt(year, 10);
    if (yearNumber < 1900 || yearNumber > new Date().getFullYear()) return false;

    // Validar correlativo (debe ser numérico y no estar vacío)
    if (!/^\d{5}$/.test(correlativo)) return false;

    return true;
}

const identityInput = document.getElementById('identity');


// Event listener para el input
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
        feedback.textContent = 'El formato es incorrecto';
        identityInput.classList.add('is-invalid');
        identityInput.classList.remove('is-valid');
    }
});

document.getElementById('email').addEventListener('input', function (e) {
    const emailInput = e.target;
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

    if (emailPattern.test(emailInput.value)) {
        emailInput.classList.remove('is-invalid');
        emailInput.classList.add('is-valid');
    } else {
        emailInput.classList.remove('is-valid');
        emailInput.classList.add('is-invalid');
    }
});

regional.addEventListener('change',async function (e) {
    mainCareer.disabled = true;
    mainCareer.innerHTML = `<option value="">Seleccione una carrera principal</option>`
    secondaryCareer.innerHTML = `<option value="">Seleccione una carrera secundaria</option>`
    secondaryCareer.disabled = true;
    
    if (e.target.value != '') {
        MyModalLoading.show();
        fetch('/api/get/public/careersByCenter.php?center_id='+e.target.value)
        .then((response)=>{return response.json()})
        .then((response)=>{
            mainCareer.disabled = false;
            secondaryCareer.disabled = false;
            response.forEach(career => {
                mainCareer.innerHTML += `<option value="${career.career_id}">${career.career_name}</option>`
                secondaryCareer.innerHTML += `<option value="${career.career_id}">${career.career_name}</option>`
            });
            MyModalLoading.hide();
        })
    }
    
})

const mainCareerSelect = document.getElementById('mainCareer');
const secondaryCareerSelect = document.getElementById('secondaryCareer');

// Función para manejar la habilitación/deshabilitación de opciones
function updateOptions(selectedSelect, otherSelect) {
    // Obtener el valor seleccionado
    const selectedValue = selectedSelect.value;

    // Recorrer todas las opciones del otro select
    Array.from(otherSelect.options).forEach(option => {
        if (option.value === selectedValue && selectedValue !== "") {
            option.disabled = true; // Deshabilitar la opción si está seleccionada en el otro select
        } else {
            option.disabled = false; // Habilitar la opción si no está seleccionada
        }
    });
}

// Agregar controladores de eventos para ambos select
mainCareerSelect.addEventListener('change', () => {
    updateOptions(mainCareerSelect, secondaryCareerSelect);
});

secondaryCareerSelect.addEventListener('change', () => {
    updateOptions(secondaryCareerSelect, mainCareerSelect);
});

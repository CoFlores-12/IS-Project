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
    if (!form.checkValidity()) {
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
                toastTitle.innerHTML ='Proceso de Admisión'
                toastBody.innerHTML = `<div class="alert alert-success mb-0" role="alert">
                ${res.message}
                </div>`
                toastBS.show();
                getClassesView();
                tableSections.innerHTML = '';
                form.reset();
                form.classList.remove("was-validated");
            }else {
                sendBtn.disabled = false;
                toastTitle.innerHTML ='Proceso de Admisión'
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

document.getElementById('identity').addEventListener('input', function (e) {
    let input = e.target;
    let value = input.value.replace(/\D/g, ''); 
    let formattedValue = '';

    if (value.length > 0) {
        formattedValue = value.substring(0, 4); 
    }
    if (value.length > 4) {
        formattedValue += '-' + value.substring(4, 8); 
    }
    if (value.length > 8) {
        formattedValue += '-' + value.substring(8, 13); 
    }

    input.value = formattedValue;
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
const personalInputEmail = document.getElementById('personalInputEmail');
const btnEnviar = document.getElementById('btnEnviar');

const formInput = document.getElementById('formInput');
const loading = document.getElementById('loading');
loading.style.display = "none";

const alertNoEmail = document.getElementById('alertNoEmail');
alertNoEmail.style.display = "none";

const sendEmail = document.getElementById('sendEmail');
sendEmail.style.display = "none"

const checkPersonallEmail = (email) => {
    fetch(`/api/get/students/verifyMail.php?personal_email=${encodeURIComponent(email)}`, {
        method: 'GET',
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.status == 0) {
                newToken(data.student_id)
            } else {
                alertNoEmail.style.display = "block"
                alertNoEmail.removeAttribute('hidden');
                loading.style.display = "none";
            }
        })
        .catch((error) => {
            console.error('Error:', error);
        });
};

const newToken = (ID) => {
    fetch('/api/post/students/ResetPasswordStudent.php', {
        method: 'POST', 
        headers: {
            'Content-Type': 'application/json', 
        },
        body: JSON.stringify({ student_identifier: ID, personal_email: personalInputEmail.value}), 
    })
    .then(response => response.json())
    .then(data => {   
        sendEmail.style.display = "block"
        sendEmail.removeAttribute('hidden');
        loading.style.display = "none";
        console.log(data.status)
    })
    .catch(error => {
            
    });
};



btnEnviar.addEventListener("click", ()=>{
    if(personalInputEmail.value == ""){ return};
    loading.style.display = "block";
    loading.removeAttribute('hidden');
    checkPersonallEmail(personalInputEmail.value)
    formInput.style.display = "none";
})

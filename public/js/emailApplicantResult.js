
const sendMails = document.getElementById('sendMails');
const alertSuccesMails = document.getElementById('alertSuccesMails');
const alertFalitedMails = document.getElementById('alertFalitedMails');

alertSuccesMails.style.display = "none"

alertFalitedMails.style.display = "none"


//https://is-project-fixes.up.railway.app/api/post/admissions/sendMail.php

sendMails.addEventListener("click", ()=>{
    sendMails.innerHTML = `<div class="spinner-border" role="status">
                            <span class="visually-hidden">Cargando...</span>
                            </div>`
    sendMails.disabled = true;
    activateEmailSend();
})

function activateEmailSend() {
 
        fetch('/api/put/admin/configurationMail.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {

            if (data == true) {
                alertSuccesMails.style.display = "block"
                setTimeout(function() {
                    alertSuccesMails.style.display = 'none';
                }, 3000); 
            sendMails.innerHTML = `Mandar correos`
            sendMails.disabled = false;
            } else {
                alertFalitedMails.style.display = "block"
                setTimeout(function() {
                    alertFalitedMails.style.display = 'none';
                }, 3000); 
            }
        })
        .catch(error => console.error('Error:', error));
  }





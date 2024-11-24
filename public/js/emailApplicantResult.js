
const sendMails = document.getElementById('sendMails');
const alertSuccesMails = document.getElementById('alertSuccesMails');
const alertFalitedMails = document.getElementById('alertFalitedMails');

alertSuccesMails.style.display = "none"

alertFalitedMails.style.display = "none"

function sentMails (){
    fetch('/api/post/admissions/sendMail.php', {
        method: 'GET',
    })
        .then(response => response.json())
        .then(data => {
            alertSuccesMails.style.display = "block"
            setTimeout(function() {
                alertSuccesMails.style.display = 'none';
              }, 3000); 
              sendMails.innerHTML = `Mandar correos`
            sendMails.disabled = false;
        })
        .catch(error => {
            alertFalitedMails.style.display = "block"
            setTimeout(function() {
                alertFalitedMails.style.display = 'none';
              }, 3000); 
        });


}

sendMails.addEventListener("click", ()=>{
    sendMails.innerHTML = `<div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                            </div>`
    sendMails.disabled = true;


    sentMails();
})



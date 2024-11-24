const submitButton = document.getElementById('submit-button');
const inputPassword = document.getElementById('inputPassword');
        
const alertExpired = document.getElementById('alertExpired');
alertExpired.style.display = "none"

const formInput = document.getElementById('formInput');
formInput.style.display = "none"

const alertSuccess = document.getElementById('alertSuccess');
alertSuccess.style.display = "none"

const urlParams = new URLSearchParams(window.location.search);
const token = urlParams.get('token');

fetch(`/api/get/admin/verifyToken.php?token=${token}`)
.then(response => response.json())
.then(data => {

    console.log(data)

    if (data.status === 0) {
        formInput.style.display = "block"
    } else {
        alertExpired.style.display = "block"
    }
   })
 .catch(error => {
    console.error('Error:', error);
 });


submitButton.addEventListener('click', () => {
const newPassword = inputPassword.value;

if(newPassword == ""){
    return;
}
    
    fetch('/api/put/students/resetPassword.php', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            token:token, 
            new_password:newPassword
        })
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 0){
            alertSuccess.style.display = "block"
            formInput.style.display = "none"
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating password.');
    });


    
})
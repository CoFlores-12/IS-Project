const fileInput = document.getElementById('csvFile');
const sendButton = document.getElementById('sendCSV');

let alertUploadFile = document.getElementById('alertUploadFile');
alertUploadFile.style.display = 'none';

let alertUploadSuccess = document.getElementById('alertUploadSuccess');
alertUploadSuccess.style.display = 'none';

sendButton.addEventListener("click", () => {
    alertUploadFile.style.display = 'none';
    const file = fileInput.files[0];

    if (!file) {
        alertUploadFile.style.display = 'block';
        alertUploadFile.removeAttribute('hidden');
        return;
    }

    sendButton.disabled = true;
    sendButton.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Cargando...`;

    const reader = new FileReader();

    reader.onload = function(e) {
        const csvContent = e.target.result;

        fetch('/api/post/register/addStudent.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                csvData: csvContent
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                sendButton.disabled = false;
                sendButton.innerHTML = `Enviar`;
                alertUploadSuccess.style.display = 'block';
                alertUploadSuccess.removeAttribute('hidden');
                setTimeout(function() {
                    alertUploadSuccess.style.display = 'none';
                  }, 3000);
            } 
        })
        .catch(error => {
            console.error('Error:', error)
            sendButton.disabled = false;
            sendButton.innerHTML = `Enviar`;
        });
    };

    reader.readAsText(file); 
});

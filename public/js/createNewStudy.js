const fileInput = document.getElementById('csvFile');
const sendButton = document.getElementById('sendCSV');

sendButton.addEventListener("click", () => {
    const file = fileInput.files[0];

    if (!file) {
        alert("Por favor seleccione un archivo.");
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
            } 
        })
        .catch(error => console.error('Error:', error));
    };

    reader.readAsText(file); 
});

document.getElementById('csvUploadForm').addEventListener('submit', function (event) {
    event.preventDefault();

    const formData = new FormData(this);

    fetch('/api/post/admin/uploadCSV.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            document.getElementById('successMessage').textContent = data.message;
            new bootstrap.Modal(document.getElementById('successModal')).show();
        } else {
            alert(`Error: ${data.error || "Error desconocido"}`);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert(`Error en la solicitud: ${error.message}`);
    });
});
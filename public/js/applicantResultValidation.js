document.getElementById('nextActionBtn').addEventListener('click', function () {
    // Muestra el modal de carga
    var loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
    loadingModal.show();

    // Realiza la solicitud AJAX
    fetch('/api/put/applicant/applicantResultValidation.php', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' }
    })
    .then(response => {
        // Verifica que la respuesta sea JSON
        if (!response.ok) {
            throw new Error('Network response error');
        }
        return response.json();
    })
    .then(data => {
        loadingModal.hide();  // Oculta el modal de carga

        if (data.success) {
            // Muestra la modal de éxito final
            var finalSuccessModal = new bootstrap.Modal(document.getElementById('finalSuccessModal'));
            finalSuccessModal.show();
        } else {
            alert('There was a problem validating the results: ' + data.message);
        }
    })
    .catch(error => {
        loadingModal.hide();
        alert('Request Error: ' + error.message);
    });
});
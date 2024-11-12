document.getElementById('goToNextTask').addEventListener('click', function () {
    // Obtiene todas las modales abiertas actualmente
    var modals = document.querySelectorAll('.modal.show');

    fetch('https://tuservidor.com/api/ejecutar_funcion.php', {
        method: 'POS', 
        headers: {
            'Content-Type': 'application/json'  
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Error  : ${response.statusText}`);
        }
        return response.text(); 
    })
    .then(data => {
        console.log('The emails were sent successfully:', data); 
    })
    .catch(error => {
        console.error('mistake:', error);
    });

    // Cierra cada modal
    modals.forEach(modal => {
        var modalInstance = bootstrap.Modal.getInstance(modal);
        if (modalInstance) {
            modalInstance.hide();
        }
    });

    // Lógica para la preparación de la siguiente tarea
    prepareNextTask();
});

// Función para preparar la siguiente tarea
function prepareNextTask() {
    // Aquí puedes agregar cualquier lógica necesaria para configurar la siguiente tarea
    // Ejemplo: actualizar la interfaz, cargar datos nuevos, etc.
    console.log("Ready for next task.");
    // ... cualquier otra lógica necesaria
}

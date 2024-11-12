document.getElementById('goToNextTask').addEventListener('click', function () {
    // Obtiene todas las modales abiertas actualmente
    var modals = document.querySelectorAll('.modal.show');

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

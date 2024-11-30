document.addEventListener("DOMContentLoaded", function () {
    // Obtener el parámetro section_id de la URL
    const params = new URLSearchParams(window.location.search);
    const sectionId = params.get("section_id");

    if (sectionId) {
        fetch(`/api/get/admin/getSectionTitle.php?section_id=${sectionId}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    document.getElementById("section-title").textContent = "Error: " + data.error;
                } else {
                    // Actualizar el contenido del h4 con el título dinámico
                    document.getElementById("section-title").textContent = data.title;
                }
            })
            .catch(error => {
                document.getElementById("section-title").textContent = "Error al cargar los datos";
                console.error("Error al consumir la API:", error);
            });
    } else {
        document.getElementById("section-title").textContent = "Parámetro section_id no proporcionado";
    }
});


fetch('/api/get/admin/getUserRole.php')
    .then(response => response.json())
    .then(data => {
        if (data.role === 'Teacher' || data.role === 'Coordinator' || data.role === 'Department Head') {
            console.log("docente")
        } else if (data.role === 'Student') {
           console.log("estudiante")
        }
    })
    .catch(error => console.error('Error obteniendo rol:', error));

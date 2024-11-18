const roleAdminBtn = document.getElementById('roleAdministrationBtn');
const roleAdminModal = new bootstrap.Modal(document.getElementById('roleAdminModal'));
const editRoleModal = new bootstrap.Modal(document.getElementById('editRoleModal'));
let selectedPersonId = null;

// Cargar la lista de roles al abrir la modal de administración
roleAdminBtn.addEventListener('click', () => {
    fetch('/api/get/admin/employees.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('roleAdminTableBody');
            tableBody.innerHTML = '';
            data.forEach(row => {
                tableBody.innerHTML += `
                    <tr>
                        <td>${row.person_id}</td>
                        <td>${row.first_name}</td>
                        <td>${row.last_name}</td>
                        <td>${row.institute_email}</td>
                        <td>${row.role_type}</td>
                        <td><button class="btn btn-primary editRoleBtn" data-person-id="${row.person_id}">Edit</button></td>
                    </tr>
                `;
            });

            document.querySelectorAll('.editRoleBtn').forEach(button => {
                button.addEventListener('click', () => {
                    selectedPersonId = button.getAttribute('data-person-id');
                    loadRoleOptions();
                    editRoleModal.show();
                });
            });
        });
});

// Cargar opciones de roles en el dropdown
function loadRoleOptions() {
    fetch('/api/get/admin/roles.php')
        .then(response => response.json())
        .then(data => {
            const roleDropdown = document.getElementById('roleDropdown');
            roleDropdown.innerHTML = '';
            data.forEach(role => {
                roleDropdown.innerHTML += `<option value="${role.role_id}">${role.type}</option>`;
            });
        });
}

// Guardar cambios en el rol
document.getElementById('editRoleForm').addEventListener('submit', (e) => {
    e.preventDefault();
    const newRoleId = document.getElementById('roleDropdown').value;

    fetch('/api/post/admin/editEmployeeRole.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ person_id: selectedPersonId, role_id: newRoleId })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Role updated successfully!');
                editRoleModal.hide();
                roleAdminBtn.click(); // Recargar tabla
            } else {
                alert('Error updating role: ' + data.error);
            }
        });
});

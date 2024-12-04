<?php
session_start();
if (isset($_SESSION['user'])) {

    $role = $_SESSION['user']['role'];
    
    $rolesPermitidos = ['Teacher', 'Department Head', 'Coordinator'];

    if (in_array($role, $rolesPermitidos)) {
        echo json_encode([
            'html' => '<button class="btn btn-primary" id="">Abrir Mal</button>'
        ]);
    } else {
        echo json_encode(['html' => '']);
    }


} else {
    echo json_encode(['error' => 'Unauthorized']);
}
?>

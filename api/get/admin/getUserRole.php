<?php
session_start();
if (isset($_SESSION['user'])) {
    echo json_encode([
        'role' => $_SESSION['user']['role']
    ]);
} else {
    echo json_encode(['error' => 'Unauthorized']);
}
?>

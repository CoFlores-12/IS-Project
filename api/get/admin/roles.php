<?php
include './../../../src/modules/database.php';
$db = (new Database())->getConnection();

header('Content-Type: application/json');

$query = "SELECT role_id, type FROM Roles";
$stmt = $db->prepare($query);
$stmt->execute();
$result = $stmt->get_result(); // Usamos `get_result` para obtener los resultados

$roles = [];
while ($row = $result->fetch_assoc()) {
    $roles[] = $row;
}

echo json_encode($roles);
?>

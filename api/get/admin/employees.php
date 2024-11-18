<?php
include './../../../src/modules/database.php';
$db = (new Database())->getConnection();

header('Content-Type: application/json');

$query = "
    SELECT p.person_id, p.first_name, p.last_name, e.institute_email, r.type AS role_type
    FROM Persons p
    JOIN Employees e ON p.person_id = e.person_id
    JOIN Roles r ON e.role_id = r.role_id
";
$stmt = $db->prepare($query);
$stmt->execute();
$result = $stmt->get_result(); // Usamos `get_result` para obtener los resultados

$employees = [];
while ($row = $result->fetch_assoc()) {
    $employees[] = $row;
}

echo json_encode($employees);
?>

<?php
// Validar el método de la solicitud
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo json_encode([
        "status" => false,
        "message" => "Método no disponible"
    ]);
    exit;
}

$message = [];
include '../../../src/modules/database.php';
$conn = (new Database())->getConnection();

$truncateQuery = "TRUNCATE TABLE ValidatorxApplicant";
$conn->execute_query($truncateQuery);

$applicantsQuery = "SELECT applicant_id FROM Applicant WHERE status_id = 0";
$applicantsResult = $conn->execute_query($applicantsQuery);
$applicants = $applicantsResult->fetch_all(MYSQLI_ASSOC);

$validatorsQuery = "SELECT employee_number FROM Employees WHERE role_id = 6";
$validatorsResult = $conn->execute_query($validatorsQuery);
$validators = $validatorsResult->fetch_all(MYSQLI_ASSOC);

if (empty($applicants) || empty($validators)) {
    echo json_encode([
        "status" => false,
        "message" => "No hay aspirantes o validadores disponibles para el sorteo."
    ]);
    exit;
}

foreach ($validators as $validator) {
    $message[$validator['employee_number']] = 0;
}

foreach ($applicants as $applicant) {
    $randomValidator = $validators[array_rand($validators)];

    $insertQuery = "
        INSERT INTO ValidatorxApplicant (applicant_id, validator_id, obs_id)
        VALUES (?, ?, NULL)
    ";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ii", $applicant['applicant_id'], $randomValidator['employee_number']);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $message[$randomValidator['employee_number']]++;
    }

    $stmt->close();
}

$conn->close();

echo json_encode([
    "status" => true,
    "message" => $message
]);
?>

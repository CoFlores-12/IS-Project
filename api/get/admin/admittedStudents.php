<?php

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="admitteds.csv"');

include '../../../src/modules/database.php';
$conn = (new Database())->getConnection();

$result = $conn->execute_query("SELECT * FROM Applicant WHERE status = 'Admitted'");
$students = [];
while ($student = $result->fetch_assoc()) {
    $students[] = $student;
}

$output = fopen('php://output', 'w');

foreach ($students as $student) {
    fputcsv($output, $student);
}

fclose($output);
exit;
?>

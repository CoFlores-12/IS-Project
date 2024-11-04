<?php

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="admitteds.csv"');

include '../../../src/modules/database.php';
include '../../../src/models/AsppirantModel.php';

$conn = (new Database())->getConnection();

$result = Aspirant::getAdmitted($conn);
$students = [];
while ($student = $result->fetch_assoc()) {
    $students[] = $student;
}
//ge=nerate output file
$output = fopen('php://output', 'w');

foreach ($students as $student) {
    fputcsv($output, $student);
}
fclose($output);

$conn->close();
?>

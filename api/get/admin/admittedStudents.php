<?php
require_once '../../../src/modules/Auth.php';

$requiredRole = 'Admissions';

AuthMiddleware::checkAccess($requiredRole);
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
fputcsv($output, ['person_id','first_name','last_name','personal_email','preferend_career_id','secondary_career_id','approved_pref','approved_sec']);
foreach ($students as $student) {
    fputcsv($output, $student);
}
$dataString = json_encode($students);
$hash = hash('sha256', $dataString); 

fputcsv($output, ['#hash', $hash]);
fclose($output);

$conn->close();
?>

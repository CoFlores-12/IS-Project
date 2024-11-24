<?php
require_once '../../../src/modules/Auth.php';

$requiredRole = 'Admissions';

AuthMiddleware::checkAccess($requiredRole);
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="scores.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Person_id', 'Exam_code', 'Score']);

fclose($output);

?>

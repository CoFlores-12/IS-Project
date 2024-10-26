<?php

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="estudiantes_admitidos.csv"');

//TODO: get students from database
$students = [
    ['ID', 'Nombre', 'Apellido', 'Correo Electrónico'],
    [1, 'Juan', 'Pérez', 'juan.perez@example.com'],
    [2, 'María', 'González', 'maria.gonzalez@example.com'],
    [3, 'Carlos', 'López', 'carlos.lopez@example.com']
];

$output = fopen('php://output', 'w');

foreach ($students as $student) {
    fputcsv($output, $student);
}

fclose($output);
exit;
?>

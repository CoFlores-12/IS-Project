<?php 
session_start();

$role = $_SESSION['role'];
$_SESSION['request'] = 'teacher';

include '../../../../src/components/sessionValidation.php';

if ($role === 'Department Head') {
    include '../../../../src/templates/departamentHeadHome.php';
}
if ($role === 'Teacher') {
    include '../../../../src/templates/TeacherHome.php';
}

?>
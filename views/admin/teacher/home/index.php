<?php 
session_start();
$role = $_SESSION['role'];


if ($role === 'Department Head') {
    include '../../../../src/templates/departamentHeadHome.php';
}
if ($role === 'Teacher') {
    include '../../../../src/templates/TeacherHome.php';
}

?>
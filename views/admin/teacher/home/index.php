<?php 
session_start();

$role = $_SESSION['user']['role'];

if (!isset($_SESSION['user'])) {
    header("Location: /index.php");
    exit;
};

if ($role === 'Department Head') {
    include 'departamentHeadHome.php';
}
if ($role === 'Teacher') {
    include 'TeacherHome.php';
}
if ($role === 'Coordinator') {
    include 'coordinatorHome.php';
}

?>

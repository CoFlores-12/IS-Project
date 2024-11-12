<?php
ob_start();

if (!isset($_SESSION['route']) || 
    !isset($_SESSION['request'])) {
        header('Location: /api/get/logout.php');
}
if ($_SESSION['route'] != $_SESSION['request']) {
        header('Location: /api/get/logout.php');
}
ob_end_flush();
?>

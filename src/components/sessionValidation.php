<?php

if (!isset($_SESSION['route']) || 
    !isset($_SESSION['request'])) {
        header('Location: /api/get/logout.php');
}
if ($_SESSION['route'] != $_SESSION['request']) {
        header('Location: /api/get/logout.php');
}

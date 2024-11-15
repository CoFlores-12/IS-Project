<?php
require_once '../src/modules/Auth.php';

$requiredRole = 'Administrator';

AuthMiddleware::checkAccess($requiredRole);

echo "Bienvenido a la página protegida!";

<?php

$email = $_POST['email'];
$password = $_POST['password'];

include_once '../../../src/modules/Auth.php';

$auth = AuthMiddleware::Auth($email, $password, 0);

if ($auth) {
    echo json_encode([
        "status"=> $auth,
        "route"=>$_SESSION['user']['mainPage']
    ]);
}else {
    http_response_code(404);
    echo "invalid credentials";
}
exit;
?>
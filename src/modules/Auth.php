<?php

class AuthMiddleware {
    /**
     * permite autenticar a una entidad
     * @param String $identifier correo, identidad, numero de empleado, clave estudiante
     * @param String $password contraseña 
     * @param int $entity 0 = estudiante, 1 = administrador (entidad que se va a autenticar)
     * @return bool Es autenticado correctamente?
     */
    public static function Auth($identifier, $password, $entity = 0){
        include_once 'database.php';

        $response = false;
        $user = [];
        $user['ip'] = $_SERVER['REMOTE_ADDR'];
        $conn = (new Database())->getConnection();

        if ($entity === 0) {
            $result = $conn->execute_query("CALL LoginStudent(?, ?, @is_authenticated, @out_id)", [$identifier, $password]);
            $result = $conn->query("SELECT @is_authenticated AS is_authenticated, @out_id AS out_id");

            $row = $result->fetch_assoc();
            $user['is_authenticated'] = $row['is_authenticated'];
            $user['student_id'] = $row['out_id'];
            $user['role'] = 'Student';
            $user['route'] = 'student';
            $user['mainPage'] = '/views/students/home/index.php';
        }else{
            $result = $conn->execute_query("CALL LoginAdministrator(?, ?, @is_authenticated, @out_role, @out_route, @out_employee_number);", [$identifier, $password]);
            $result = $conn->query("SELECT @is_authenticated AS is_authenticated, @out_role AS role, @out_route as route, @out_employee_number as departmentid");

            $row = $result->fetch_assoc();
            $user['is_authenticated'] = $row['is_authenticated'];
            $user['role'] = $row['role'];
            $user['route'] = $row['route'];
            $user['departmentid'] = $row['departmentid'];
            $user['mainPage'] = "/views/admin/". $user['route'] ."/home/index.php";
        }

        if ($user['is_authenticated']) {
            session_start();
            $_SESSION['user'] = $user;
            $response = true;
        } 

        return $response;
    }

    /**
     * verifica que la peticion cumple con el rol requerido y la ip que inicio sesion
     * @param String $requiredRole rol requerido para realizar la peticion, 
     * Opciones:
        *     - Student
        *     - Administrator
        *     - Admissions
        *     - Register Agent
        *     - Department Head
        *     - Coordinator
        *     - Teacher
     * @return None
     */
    public static function CheckAccess($requiredRole) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            http_response_code(403);
            echo "Not logged";
            exit;
        }

        // Verifica la dirección IP de la sesión
        $sessionIp = $_SESSION['user']['ip'];
        $currentIp = $_SERVER['REMOTE_ADDR'];
        if ($currentIp !== $sessionIp) {
            http_response_code(403);
            echo "Acceso denegado: IP no coincide con la de la sesión.";
            exit;
        }

        // Verifica el rol del usuario
        $userRole = $_SESSION['user']['role'];
        if ($userRole !== $requiredRole) {
            http_response_code(403);
            echo "Acceso denegado: Rol no autorizado.".$_SESSION['user']['role'];
            exit;
        }

    }
}

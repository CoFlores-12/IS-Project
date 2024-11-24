<?php
class Aspirant {
    private $identity;
    public $firstName;
    public $lastName;
    private $phone;
    public $email;
    private $mainCareer;
    private $secondaryCareer;
    private $regionalCenter;
    private $certifyFile;
    private $certifyExt;
    private $errors = [];

    /**
     * constructor for new Aspirant.
     * @param array<any> all data from aspirant.
     * @return Aspirant object .
     */
    public function __construct($data, $fileContent, $fileExtension){
        $this->identity = $this->sanitize($data['identity'] ?? '');
        $this->firstName = $this->sanitize($data['name'] ?? '');
        $this->lastName = $this->sanitize($data['lastName'] ?? '');
        $this->phone = $this->sanitize($data['phone'] ?? '');
        $this->email = $this->sanitize($data['email'] ?? '');
        $this->mainCareer = $data['mainCareer'] ?? '';
        $this->secondaryCareer = $data['secondaryCareer'] ?? '';
        $this->regionalCenter = $data['regionalCenter'] ?? '';
        $this->certifyFile = $fileContent;
        $this->certifyExt = $fileExtension;
    }

    /**
     * convert string to clean string, delete HTML & JS inyection.
     * @param string whithout processing.
     * @return string processed.
     */
    private function sanitize($input){
        return htmlspecialchars(strip_tags(trim($input)));
    }

    /**
     * validate all attributes of Aspirant.
     * @return Boolean errors ? true : false.
     */
    public function validate(){
        if (empty($this->firstName) || !preg_match("/^[a-zA-Z\s]+$/", $this->firstName)) {
            $this->errors['first_name'] = "El nombre debe contener solo letras y espacios.";
        }
        if (empty($this->lastName) || !preg_match("/^[a-zA-Z\s]+$/", $this->lastName)) {
            $this->errors['last_name'] = "El apellido debe contener solo letras y espacios.";
        }
        if (empty($this->identity) || !preg_match("/^\d{4}-\d{4}-\d{5}$/", $this->identity)) {
            $this->errors['identity'] = "La identificación debe tener el formato XXXX-XXXX-XXXXX.";
        }
        if (empty($this->phone) || !preg_match("/^[389]\d{3}-\d{4}$/", $this->phone)) {
            $this->errors['phone'] = "El número de teléfono debe comenzar con 3, 8 o 9 y seguir el formato XXXX-XXXX.";
        }
        if (empty($this->email) || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = "La dirección de correo electrónico no es válida.";
        }
        if (empty($this->mainCareer)) {
            $this->errors['mainCareer'] = "Debe seleccionar una carrera principal.";
        }
        if (empty($this->secondaryCareer)) {
            $this->errors['secondaryCareer'] = "Debe seleccionar una carrera secundaria.";
        }
        if (empty($this->regionalCenter)) {
            $this->errors['regionalCenter'] = "Debe seleccionar un centro regional.";
        }

        return empty($this->errors);
    }

    
    /**
     * save Aspirant in database.
     * @param Database connection to database.
     * @return string processed.
     */
    public function save($conn){
        
        if (!$this->validate()) {
            return false;
        }

        $this->identity = str_replace("-", "", $this->identity);
        $this->phone = str_replace("-", "", $this->phone);

        try {
            $sql = "SELECT status_id, counter FROM Applicant WHERE person_id = ? AND (status_id = 1 OR status_id = 0 OR status_id = 2)";
            $result = $conn->execute_query($sql, [$this->identity]);
            $row = $result->fetch_assoc();
            $status = $row['status_id'] ?? null;
            $counter = $row['counter'] ?? 0;

            if ($counter >= 3) {
                $this->errors['counter'] = "El aspirante no puede registrarse porque ha alcanzado el límite máximo de 3 intentos.";
                return false;
            }
        
            if (is_null($status) || $status === 2) {
                if ($status === 2) {
                    // Incrementar el contador y cambiar `status_id` a 0
                    $counter++;
                    $sql = "UPDATE Applicant 
                    SET counter = ?, status_id = 0, preferend_career_id = ?, secondary_career_id = ? 
                    WHERE person_id = ?";
                    $conn->execute_query($sql, [
                        $counter,
                        $this->mainCareer,
                        $this->secondaryCareer,
                        $this->identity
                    ]);
                    return true;
                } else {
                    // Insertar en `Persons` si no existe previamente
                    $sql = "INSERT IGNORE INTO `Persons` (person_id, first_name, last_name, phone, personal_email, center_id) 
                            VALUES (?, ?, ?, ?, ?, ?)";
                    $conn->execute_query($sql, [
                        $this->identity,
                        $this->firstName,
                        $this->lastName,
                        $this->phone,
                        $this->email,
                        $this->regionalCenter
                    ]);
        
                    // Insertar en `Applicant` con status_id 0
                    $sql = "INSERT INTO `Applicant` (
                        person_id, 
                        preferend_career_id, 
                        secondary_career_id, 
                        certify, 
                        certify_ext, 
                        status_id
                    ) VALUES (?, ?, ?, ?, ?, 0)";
                    $conn->execute_query($sql, [
                        $this->identity,
                        $this->mainCareer,
                        $this->secondaryCareer,
                        $this->certifyFile, 
                        $this->certifyExt
                    ]);
                    return true;
                }
            } else {
                $status = $status == 0 ? 'Pendiente' : 'Admitido';
                $this->errors['status'] = "El aspirante no puede registrarse porque su estado es $status.";
                return false;
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $this->errors['database'] = "El aspirante con ID $this->identity ya está en la lista.";
            } else {
                throw $e;
            }
        }

        return false;
    }

    /**
     * return all errors in data.
     * @return array<any> all errors found.
     */
    public function getErrors(){
        return $this->errors;
    }

    /**
     * get all Aspirant admitted from database.
     * @param Database connection to database.
     * @return mixed
     */
    public static function getAdmitted($conn){
        $exported =  $conn->execute_query("SELECT 
                                        P.person_id,
                                        P.first_name,
                                        P.last_name,
                                        P.personal_email,
                                        A.preferend_career_id,
                                        A.secondary_career_id,
                                        A.approved_pref,
                                        A.approved_sec
                                    FROM 
                                        Applicant A
                                    JOIN 
                                        Persons P ON A.person_id = P.person_id
                                    WHERE 
                                        A.status_id = 1;");
        $conn->execute_query('UPDATE `Applicant` SET status_id = 4 WHERE  A.status_id = 1;');
        return $exported;
    }

    public function toArray() {
        return [
            'Identificación' => $this->identity,
            'Nombre' => $this->firstName . ' ' . $this->lastName,
            'Teléfono' => $this->phone,
            'Correo' => $this->email,
            'Carrera preferida' => $this->mainCareer,
            'Carrera secundaria' => $this->secondaryCareer,
            'Centro regional' => $this->regionalCenter,
        ];
    }
}

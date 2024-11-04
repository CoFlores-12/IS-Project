<?php
class Aspirant
{
    private $identity;
    private $firstName;
    private $lastName;
    private $phone;
    private $email;
    private $mainCareer;
    private $secondaryCareer;
    private $regionalCenter;
    private $certifyFile;
    private $errors = [];

    /**
     * constructor for new Aspirant.
     * @param array<any> all data from aspirant.
     * @return Aspirant object .
     */
    public function __construct($data, $file){
        $this->identity = $this->sanitize($data['identity'] ?? '');
        $this->firstName = $this->sanitize($data['name'] ?? '');
        $this->lastName = $this->sanitize($data['lastName'] ?? '');
        $this->phone = $this->sanitize($data['phone'] ?? '');
        $this->email = $this->sanitize($data['email'] ?? '');
        $this->mainCareer = $data['mainCareer'] ?? '';
        $this->secondaryCareer = $data['secondaryCareer'] ?? '';
        $this->regionalCenter = $data['regionalCenter'] ?? '';
        $this->certifyFile = $file;
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
            $this->errors['first_name'] = "The first name should contain only letters and spaces.";
        }
        if (empty($this->lastName) || !preg_match("/^[a-zA-Z\s]+$/", $this->lastName)) {
            $this->errors['last_name'] = "The last name should contain only letters and spaces.";
        }
        if (empty($this->identity) || !preg_match("/^\d{4}-\d{4}-\d{5}$/", $this->identity)) {
            $this->errors['identity'] = "The identity must have the format XXXX-XXXX-XXXXX.";
        }
        if (empty($this->phone) || !preg_match("/^[389]\d{3}-\d{4}$/", $this->phone)) {
            $this->errors['phone'] = "The phone number must start with 3, 8, or 9 and follow the format XXXX-XXXX.";
        }
        if (empty($this->email) || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = "The email address is not valid.";
        }
        if (empty($this->mainCareer)) {
            $this->errors['mainCareer'] = "You must select a main career.";
        }
        if (empty($this->secondaryCareer)) {
            $this->errors['secondaryCareer'] = "You must select a secondary career.";
        }
        if (empty($this->regionalCenter)) {
            $this->errors['regionalCenter'] = "You must select a regional center.";
        }

        return empty($this->errors);
    }

    /**
     * save certify of aspirant in file system.
     * @return string name of file saved.
     */
    public function saveCertifyFile(){
        $uploadDir = '../../../uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileExtension = pathinfo($this->certifyFile['name'], PATHINFO_EXTENSION);
        $newFileName = 'certify_' . str_replace("-", "", $this->identity) . '.' . $fileExtension;
        $uploadPath = $uploadDir . $newFileName;

        if (move_uploaded_file($this->certifyFile['tmp_name'], $uploadPath)) {
            return $newFileName;
        } else {
            $this->errors['certify'] = "Error uploading the file.";
            return false;
        }
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
        $certifyFileName = $this->saveCertifyFile();

        if (!$certifyFileName) {
            return false;
        }

        try {
            $sql = "SELECT status FROM Applicant WHERE person_id = ? AND (status = 'Admitted' OR status = 'Pendient')";
            $result = $conn->execute_query($sql, [$this->identity]);
            $status = $result->fetch_assoc()['status'] ?? null;

            if (is_null($status) || $status === 'Not Admitted') {
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

                $sql = "INSERT INTO `Applicant` (person_id, preferend_career_id, secondary_career_id, certify, status) 
                        VALUES (?, ?, ?, ?, 'Pendient')";
                $conn->execute_query($sql, [
                    $this->identity,
                    $this->mainCareer,
                    $this->secondaryCareer,
                    $certifyFileName
                ]);
                return true;
            } else {
                $this->errors['status'] = "The applicant cannot register because their status is $status.";
                return false;
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $this->errors['database'] = "The applicant with ID $this->identity is already on the list.";
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
        return $conn->execute_query("SELECT * FROM Applicant WHERE status = 'Admitted'");
    }
}
?>

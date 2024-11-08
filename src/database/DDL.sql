SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE StatusApplicant;
DROP TABLE `Applicant`;
DROP TABLE Roles;
DROP TABLE `Applicant_result`;
DROP TABLE `Careers`;
DROP TABLE `Classes`;
DROP TABLE `ClassesXCareer`;
DROP TABLE `Exams`;
DROP TABLE `ExamsXCareer`;
DROP TABLE `Faculty`;
DROP TABLE `Persons`;
DROP TABLE `Regional_center`;
DROP TABLE `Students`;
DROP TABLE `Administrators`;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE Regional_center (
    center_id INT PRIMARY KEY AUTO_INCREMENT,
    center_name VARCHAR(100) NOT NULL
);

CREATE TABLE Persons (
    person_id VARCHAR(20) PRIMARY KEY, -- (identity_number)
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    phone VARCHAR(15),
    personal_email VARCHAR(100),
    center_id INT,
    FOREIGN KEY (center_id) REFERENCES Regional_center(center_id)
);

CREATE TABLE Faculty (
    faculty_id INT PRIMARY KEY AUTO_INCREMENT,
    faculty_name VARCHAR(100) NOT NULL
);

CREATE TABLE CareersXRegionalCenter (
    career_id INT,
    center_id INT,
    Foreign Key (career_id) REFERENCES Careers(career_id),
    Foreign Key (center_id) REFERENCES Regional_center(center_id)
);
CREATE TABLE Careers (
    career_id INT PRIMARY KEY AUTO_INCREMENT,
    career_name VARCHAR(100) NOT NULL,
    time_stm TINYINT,
    faculty_id INT,
    FOREIGN KEY (faculty_id) REFERENCES Faculty(faculty_id)
);

CREATE TABLE Exams (
    exam_code VARCHAR(20) PRIMARY KEY,
    exam_name VARCHAR(100) NOT NULL
);

CREATE TABLE ExamsXCareer (
    exam_code VARCHAR(20),
    career_id INT,
    min_point FLOAT,
    PRIMARY KEY (exam_code, career_id),
    FOREIGN KEY (exam_code) REFERENCES Exams(exam_code),
    FOREIGN KEY (career_id) REFERENCES Careers(career_id)
);

CREATE TABLE Classes (
    class_id INT PRIMARY KEY,
    class_code VARCHAR(20) NOT NULL,
    class_name VARCHAR(100) NOT NULL,
    uv TINYINT
);

CREATE TABLE ClassesXCareer (
    class_id INT,
    career_id INT,
    req JSON,
    PRIMARY KEY (class_id, career_id),
    FOREIGN KEY (class_id) REFERENCES Classes(class_id),
    FOREIGN KEY (career_id) REFERENCES Careers(career_id)
);

CREATE TABLE StatusApplicant (
    status_id TINYINT PRIMARY KEY
    description VARCHAR(20)
)

CREATE TABLE Applicant (
    applicant_id INT PRIMARY KEY AUTO_INCREMENT,
    person_id VARCHAR(20), -- (identity_number)
    preferend_career_id INT,
    secondary_career_id INT,
    certify VARCHAR(255),
    inscription_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status_id TINYINT,
    counter TINYINT DEFAULT 1,
    approved_pref BIT DEFAULT 0,
    approved_sec BIT DEFAULT 0,
    FOREIGN KEY (person_id) REFERENCES Persons(person_id),
    FOREIGN KEY (preferend_career_id) REFERENCES Careers(career_id),
    FOREIGN KEY (secondary_career_id) REFERENCES Careers(career_id),
    Foreign Key (status_id) REFERENCES StatusApplicant(Status_id)
);

CREATE TABLE Applicant_result (
    result_id INT PRIMARY KEY AUTO_INCREMENT,
    identity_number VARCHAR(20),
    exam_code VARCHAR(20),
    result_exam FLOAT,
    obs BIT --(1: passed, 0: repro)
    FOREIGN KEY (identity_number) REFERENCES Persons(person_id),
    FOREIGN KEY (exam_code) REFERENCES Exams(exam_code)
);

CREATE TABLE Students (
    account_number VARCHAR(11) NOT NULL  PRIMARY KEY,
    person_id VARCHAR(20), -- (identity_number)
    password VARBINARY(255) NOT NULL, 
    institute_email VARCHAR(100) UNIQUE,
    direction VARCHAR(255),
    photos JSON,
    career_id INT,
    FOREIGN KEY (person_id) REFERENCES Persons(person_id),
    FOREIGN KEY (career_id) REFERENCES Careers(career_id)
);

CREATE TABLE Roles (
    role_id TINYINT PRIMARY KEY,
    type VARCHAR(20) --(jefe, coordinador, docente, etc)
    route VARCHAR(20) --(admin, teacher, etc)
)
CREATE TABLE Administrators (
    employee_number INT PRIMARY KEY AUTO_INCREMENT,
    person_id VARCHAR(20),
    role_id TINYINT,
    password VARBINARY(255) NOT NULL, 
    institute_email VARCHAR(100) UNIQUE,
    FOREIGN KEY (person_id) REFERENCES Persons(person_id),
    Foreign Key (role) REFERENCES Roles(role_id)
)

CREATE TABLE Config (
    config_id BINARY PRIMARY KEY AUTO_INCREMENT,
    data JSON
)

DELIMITER //

CREATE PROCEDURE CreateAdministrator (
    IN in_person_id VARCHAR(20),
    IN in_role VARCHAR(25),
    IN in_password VARCHAR(255),
    IN in_institute_email VARCHAR(100)
)
BEGIN
    DECLARE secret_key VARCHAR(255); 

    SELECT JSON_UNQUOTE(JSON_EXTRACT(data, '$.phraseEncrypt'))
    INTO secret_key
    FROM Config
    WHERE config_id = 1;

    START TRANSACTION;

    INSERT INTO Administrators (person_id, role, password, institute_email)
    VALUES (
        in_person_id,
        in_role,
        AES_ENCRYPT(in_password, secret_key),
        in_institute_email
    );

    COMMIT;
END //

DELIMITER //

CREATE PROCEDURE LoginAdministrator (
    IN in_identifier VARCHAR(100),      
    IN in_password VARCHAR(255),       
    OUT is_authenticated BOOLEAN,       
    OUT out_role VARCHAR(25)            
)
BEGIN
    DECLARE secret_key VARCHAR(255);     
    DECLARE db_password VARBINARY(255);   

    SET is_authenticated = FALSE;
    SET out_role = '0';

    SELECT JSON_UNQUOTE(JSON_EXTRACT(data, '$.phraseEncrypt'))
    INTO secret_key
    FROM Config
    WHERE config_id = 1;

    SELECT password, role INTO db_password, out_role
    FROM Administrators
    WHERE (institute_email = in_identifier OR employee_number = in_identifier);

    IF db_password IS NOT NULL AND AES_DECRYPT(db_password, secret_key) = in_password THEN
        SET is_authenticated = TRUE;
    ELSE
        SET out_role = '0'; 
    END IF;
END //

DELIMITER ;



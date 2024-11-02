SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE `Applicant`;
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

CREATE TABLE Applicant (
    applicant_id INT PRIMARY KEY AUTO_INCREMENT,
    person_id VARCHAR(20), -- (identity_number)
    preferend_career_id INT,
    secondary_career_id INT,
    certify VARCHAR(255),
    inscription_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(50),
    FOREIGN KEY (person_id) REFERENCES Persons(person_id),
    FOREIGN KEY (preferend_career_id) REFERENCES Careers(career_id),
    FOREIGN KEY (secondary_career_id) REFERENCES Careers(career_id)
);

CREATE TABLE Applicant_result (
    result_id INT PRIMARY KEY AUTO_INCREMENT,
    identity_number VARCHAR(20),
    result FLOAT,
    exam_code VARCHAR(20),
    FOREIGN KEY (identity_number) REFERENCES Persons(person_id),
    FOREIGN KEY (exam_code) REFERENCES Exams(exam_code)
);

CREATE TABLE Students (
    account_number VARCHAR(11) NOT NULL  PRIMARY KEY,
    person_id VARCHAR(20), -- (identity_number)
    password VARCHAR(255) NOT NULL, 
    institute_email VARCHAR(100) UNIQUE,
    direction VARCHAR(255),
    photos JSON,
    career_id INT,
    FOREIGN KEY (person_id) REFERENCES Persons(person_id),
    FOREIGN KEY (career_id) REFERENCES Careers(career_id)
);

CREATE TABLE Administrators (
    employee_number INT PRIMARY KEY AUTO_INCREMENT,
    person_id VARCHAR(20),
    role VARCHAR(25),
    password VARCHAR(255) NOT NULL, 
    institute_email VARCHAR(100) UNIQUE,
    FOREIGN KEY (person_id) REFERENCES Persons(person_id)
)

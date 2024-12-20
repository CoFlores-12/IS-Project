    SET FOREIGN_KEY_CHECKS = 0;
    DROP TABLE `Messages`;
    DROP TABLE `Applicant`;
    DROP TABLE `Enroll`;
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
    DROP TABLE `Requests`;
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

    CREATE TABLE Roles (
        role_id TINYINT PRIMARY KEY,
        type VARCHAR(20), --(jefe, coordinador, docente, etc)
        route VARCHAR(20) --(admin, teacher, etc)
    );

    CREATE TABLE Employees (
        employee_number INT PRIMARY KEY AUTO_INCREMENT,
        person_id VARCHAR(20),
        role_id TINYINT,
        password VARBINARY(255) NOT NULL, 
        department_id INT,
        institute_email VARCHAR(100) UNIQUE,
        FOREIGN KEY (person_id) REFERENCES Persons(person_id),
        Foreign Key (role_id) REFERENCES Roles(role_id)
    );

    CREATE TABLE Careers (
        career_id INT PRIMARY KEY AUTO_INCREMENT,
        career_name VARCHAR(100) NOT NULL,
        time_stm TINYINT,
        faculty_id INT,
        coordinator_id INT,
        Foreign Key (coordinator_id) REFERENCES Employees(employee_number),
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
        status_id TINYINT PRIMARY KEY,
        description VARCHAR(20)
    )

    CREATE TABLE Applicant (
        applicant_id INT PRIMARY KEY AUTO_INCREMENT,
        person_id VARCHAR(20), -- (identity_number)
        preferend_career_id INT,
        secondary_career_id INT,
        inscription_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        status_id TINYINT,
        counter TINYINT DEFAULT 1,
        approved_pref BIT DEFAULT 0,
        approved_sec BIT DEFAULT 0,
        certify MEDIUMBLOB,
        certify_ext VARCHAR(6),
        email_sent BIT DEFAULT 0,
        FOREIGN KEY (person_id) REFERENCES Persons(person_id),
        FOREIGN KEY (preferend_career_id) REFERENCES Careers(career_id),
        FOREIGN KEY (secondary_career_id) REFERENCES Careers(career_id),
        Foreign Key (status_id) REFERENCES StatusApplicant(status_id)
    );

    CREATE Table obsReviews (
        obsReview_id INT PRIMARY KEY AUTO_INCREMENT,
        comment TEXT
    )

    CREATE Table ValidatorxApplicant (
        review_id int PRIMARY KEY AUTO_INCREMENT,
        applicant_id INT NOT NULL,
        validator_id INT NOT NULL,
        is_valid BIT DEFAULT NULL,
        obs_id INT,
        Foreign Key (applicant_id) REFERENCES Applicant(applicant_id),
        Foreign Key (validator_id) REFERENCES Employees(employee_number),
        Foreign Key (obs_id) REFERENCES obsReviews(obsReview_id)
    )

    CREATE TABLE Applicant_result (
        result_id INT PRIMARY KEY AUTO_INCREMENT,
        identity_number VARCHAR(20),
        exam_code VARCHAR(20),
        result_exam FLOAT,
        obs BIT, --(1: passed, 0: repro)
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

    CREATE TABLE Config (
        config_id BINARY PRIMARY KEY AUTO_INCREMENT,
        data JSON
    )

    CREATE TABLE `Periods` (
    `period_id` int PRIMARY KEY AUTO_INCREMENT,
    `indicator` tinyint,
    `year` SMALLINT,
    `active` bit
    );

    CREATE TABLE `Building` (
    `building_id` int PRIMARY KEY AUTO_INCREMENT,
    `building_name` varchar(20),
    `center_id` int,
    Foreign Key (center_id) REFERENCES Regional_center(center_id)
    );

    CREATE TABLE `Classroom` (
    `classroom_id` int PRIMARY KEY AUTO_INCREMENT,
    `classroom_name` varchar(20),
    `building_id` int,
    `capacity` tinyint,
    Foreign Key (building_id) REFERENCES Building(building_id)
    );

    CREATE TABLE `Section` (
    `section_id` int PRIMARY KEY AUTO_INCREMENT,
    `class_id` int,
    `hour_start` smallint,
    `hour_end` smallint,
    `period_id` int,
    `classroom_id` int,
    `employee_number` INT,
    `quotas` INT,
    Foreign Key (class_id)  REFERENCES Classes(class_id),
    Foreign Key (period_id) REFERENCES Periods(period_id),
    Foreign Key (classroom_id) REFERENCES Classroom(classroom_id),  
    Foreign Key (employee_number) REFERENCES Employees(employee_number)
    );

    CREATE TABLE `Obs` (
        obs_id TINYINT PRIMARY KEY,
        obs_name VARCHAR(20)
    )

    CREATE TABLE `History` (
        history_id INT PRIMARY KEY AUTO_INCREMENT,
        section_id INT,
        student_id VARCHAR(11),
        score TINYINT,
        obs_id TINYINT,
        Foreign Key (section_id) REFERENCES Section(section_id),
        Foreign Key (student_id) REFERENCES Students(account_number),
        Foreign Key (obs_id) REFERENCES Obs(obs_id)
    )
    

    CREATE TABLE SectionDays (
        section_id INT,
        Monday BIT  DEFAULT 0,
        Tuesday BIT  DEFAULT 0,
        Wednesday BIT  DEFAULT 0,
        Thursday BIT  DEFAULT 0,
        Friday BIT  DEFAULT 0,
        Saturday BIT  DEFAULT 0,
        PRIMARY KEY (section_id),
        FOREIGN KEY (section_id) REFERENCES Section(section_id)
    );


    /*modifications for the employee's department*/
    CREATE TABLE Departments (
        department_id INT PRIMARY KEY AUTO_INCREMENT,
        department_name VARCHAR(100) NOT NULL
    );

    CREATE TABLE Enroll (
        enroll_id INT PRIMARY KEY AUTO_INCREMENT,
        section_id INT,
        student_id VARCHAR(11),
        is_waitlist BIT DEFAULT 0,
        is_canceled BIT DEFAULT 0,
        score TINYINT(3),
        obs_id TINYINT,
        Foreign Key (section_id) REFERENCES Section(section_id),
        Foreign Key (student_id) REFERENCES Students(account_number),
        Foreign Key (obs_id) REFERENCES Obs(obs_id)
    )

    CREATE TABLE ClassesCanceled (
        section_id INT,
        student_id VARCHAR(11),
        Foreign Key (section_id) REFERENCES Section(section_id),
        Foreign Key (student_id) REFERENCES Students(account_number)
    )
    CREATE TABLE CancelledSections (
        cancel_id INT AUTO_INCREMENT PRIMARY KEY,  
        section_id INT NOT NULL,                    
        cancel_date DATE NOT NULL,                  
        reason VARCHAR(255) NULL,                  
        cancelled_by INT NULL  
    );


    CREATE TABLE LogAuth (
        log_id INT PRIMARY KEY AUTO_INCREMENT,
        DATE TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        ip_address VARCHAR(15),
        auth_status BIT,
        role_id TINYINT,
        identifier VARCHAR(50),
        Foreign Key (role_id) REFERENCES Roles(role_id)
    );
    
    CREATE TABLE PasswordResetTokens (
    id_passwordResetTokens INT AUTO_INCREMENT PRIMARY KEY,
    identifier VARCHAR(255) NOT NULL,
    token VARCHAR(255) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    expires_at DATETIME NOT NULL,
    is_used BIT(1) DEFAULT 0
    );

CREATE TABLE ChatsGroups (
    group_id INT AUTO_INCREMENT PRIMARY KEY,
    group_name VARCHAR(255) NOT NULL,
    group_photo TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_by VARCHAR(20) NOT NULL,
    FOREIGN KEY (created_by) REFERENCES Persons(person_id)
);
CREATE TABLE Chats (
    chat_id INT AUTO_INCREMENT PRIMARY KEY,
    is_group BOOLEAN NOT NULL,
    group_id INT,
    secret TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    Foreign Key (group_id) REFERENCES ChatsGroups(group_id)
);
CREATE TABLE ChatParticipants (
    participant_id INT AUTO_INCREMENT PRIMARY KEY,
    chat_id INT NOT NULL, 
    person_id VARCHAR(20) NOT NULL,
    FOREIGN KEY (chat_id) REFERENCES Chats(chat_id) ON DELETE CASCADE,
    FOREIGN KEY (person_id) REFERENCES Persons(person_id) ON DELETE CASCADE
);

CREATE TABLE Messages (
    message_id INT AUTO_INCREMENT PRIMARY KEY, 
    sender_id VARCHAR(20) NOT NULL,
    chat_id INT, 
    content TEXT NOT NULL,
    fileContent MEDIUMBLOB,
    file_extension VARCHAR(10),
    file_name VARCHAR(255),
    sent_at DATETIME DEFAULT CURRENT_TIMESTAMP, 
    status TINYINT(1) DEFAULT 0,
    FOREIGN KEY (sender_id) REFERENCES Persons(person_id),
    FOREIGN KEY (chat_id) REFERENCES Chats(chat_id) ON DELETE CASCADE
);

CREATE TABLE RequestTypes (
  request_type_id TINYINT PRIMARY key AUTO_INCREMENT,
  title VARCHAR(50)
);

CREATE TABLE `Requests` (
  `request_id` int PRIMARY KEY AUTO_INCREMENT,
  `student_id` VARCHAR(11) not NULL,
  `request_type_id` TINYINT,
  `date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `period_id` int,
  `status` BIT,
  `comments` TEXT,
  `response` TEXT,
  `evidence` mediumblob,
  `evidence_ext` VARCHAR(5),
  `career_change_id` int,
  `campus_change_id` int,
  classes_cancel JSON,
  Foreign Key (request_type_id) REFERENCES RequestTypes(request_type_id),
  Foreign Key (student_id) REFERENCES Students(account_number),
  Foreign Key (career_change_id) REFERENCES Careers(career_id),
  Foreign Key (campus_change_id) REFERENCES Regional_center(center_id),
  Foreign Key (period_id) REFERENCES Periods(period_id)
);

CREATE TABLE class_resources (
    id INT AUTO_INCREMENT PRIMARY KEY,
    section_id INT NOT NULL,           
    video_url VARCHAR(255) NOT NULL,   
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE contact_requests (
    id_contact_requests INT AUTO_INCREMENT PRIMARY KEY,
    sender_id VARCHAR(20) NOT NULL, 
    receiver_id VARCHAR(20) NOT NULL,
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending', 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE contact_requests (
    request_id INT PRIMARY KEY AUTO_INCREMENT,
    sender_id VARCHAR(20) NOT NULL, 
    receiver_id VARCHAR(20) NOT NULL, 
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',  
    request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    FOREIGN KEY (sender_id) REFERENCES Students(account_number) ON DELETE CASCADE,  
    FOREIGN KEY (receiver_id) REFERENCES Students(account_number) ON DELETE CASCADE 
);


CREATE TABLE student_teacher_evaluation (
    evaluation_id INT AUTO_INCREMENT PRIMARY KEY,         
    student_account_number VARCHAR(20) NOT NULL, 
    teacher_id INT NOT NULL,                  
    responses JSON NOT NULL,                  
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    section_id INT
);

DELIMITER //

    CREATE PROCEDURE CreateAdministrator (
        IN in_person_id VARCHAR(20),
        IN in_role VARCHAR(25),
        IN in_password VARCHAR(255),
        IN in_institute_email VARCHAR(100),
        IN in_departament_id int
    )
    BEGIN
        DECLARE secret_key VARCHAR(255); 

        SELECT JSON_UNQUOTE(JSON_EXTRACT(data, '$.phraseEncrypt'))
        INTO secret_key
        FROM Config
        WHERE config_id = 1;

        START TRANSACTION;

        INSERT INTO `Employees` (person_id, role_id, password, institute_email, department_id)
        VALUES (
            in_person_id,
            in_role,
            AES_ENCRYPT(in_password, secret_key),
            in_institute_email,
            in_departament_id
        );

        COMMIT;
    END //

    DELIMITER //

    CREATE PROCEDURE loginStudent (
        IN in_identifier VARCHAR(100),      
        IN in_password VARCHAR(255),       
        OUT is_authenticated BOOLEAN,       
        OUT out_id VARCHAR(25)   
    )
    BEGIN
        DECLARE secret_key VARCHAR(255);     
        DECLARE db_password VARBINARY(255);   

        SET is_authenticated = FALSE;
        SET out_id = '';

        SELECT JSON_UNQUOTE(JSON_EXTRACT(data, '$.phraseEncrypt'))
        INTO secret_key
        FROM Config
        WHERE config_id = 1;

        SELECT password, person_id INTO db_password, out_id
        FROM `Students`
        WHERE (institute_email = in_identifier OR account_number = in_identifier);

        IF db_password IS NOT NULL AND AES_DECRYPT(db_password, secret_key) = in_password THEN
            SET is_authenticated = TRUE;
        ELSE
            SET out_id = ''; 
        END IF;
    END //

    DELIMITER ;

CREATE PROCEDURE LoginAdministrator (
        IN in_identifier VARCHAR(100),      
        IN in_password VARCHAR(255),
        OUT is_authenticated BOOLEAN,       
        OUT out_role VARCHAR(25),
        OUT out_route VARCHAR(25),
        OUT out_employee_number INT       
    )
    BEGIN
        DECLARE secret_key VARCHAR(255);     
        DECLARE db_password VARBINARY(255);

        SET is_authenticated = FALSE;
        SET out_role = NULL;
        SET out_route = NULL;
        SET out_employee_number = NULL;

        SELECT JSON_UNQUOTE(JSON_EXTRACT(data, '$.phraseEncrypt'))
        INTO secret_key
        FROM Config
        WHERE config_id = 1;

        SELECT A.password, B.type, B.route, A.employee_number INTO db_password, out_role, out_route, out_employee_number
        FROM `Employees` A
        INNER JOIN `Roles` B
        ON A.role_id = B.role_id
        WHERE (institute_email = in_identifier OR A.employee_number = in_identifier);

        IF db_password IS NOT NULL AND AES_DECRYPT(db_password, secret_key) = in_password THEN
            SET is_authenticated = TRUE;
        ELSE
            SET out_role = NULL; 
        END IF;
    END //

    DELIMITER ;

CREATE DEFINER=`root`@`%` PROCEDURE `CheckClassroomAvailability`(
    IN in_classroom_id INT,       
    IN in_hour_start SMALLINT,     
    IN in_hour_end SMALLINT,       
    IN in_days VARCHAR(25),        
    OUT is_available BOOLEAN      
)
BEGIN
    DECLARE conflicting_sections INT DEFAULT 0;

    SELECT COUNT(*) 
    INTO conflicting_sections
    FROM Section s
    JOIN SectionDays sd ON s.section_id = sd.section_id
    WHERE s.classroom_id = in_classroom_id
      AND s.hour_start = in_hour_start       
      AND s.hour_end = in_hour_end      
      AND (
          (sd.Monday = 1 AND FIND_IN_SET('Mon', in_days) > 0) OR
          (sd.Tuesday = 1 AND FIND_IN_SET('Tue', in_days) > 0) OR
          (sd.Wednesday = 1 AND FIND_IN_SET('Wed', in_days) > 0) OR
          (sd.Thursday = 1 AND FIND_IN_SET('Thu', in_days) > 0) OR
          (sd.Friday = 1 AND FIND_IN_SET('Fri', in_days) > 0) OR
          (sd.Saturday = 1 AND FIND_IN_SET('Sat', in_days) > 0)
      );

    IF conflicting_sections > 0 THEN
        SET is_available = FALSE;
    ELSE
        SET is_available = TRUE;
    END IF;

    SELECT is_available AS classroom_availability;
END;

CREATE PROCEDURE CheckInstructorAvailability (
    IN in_employee_number INT,    
    IN in_hour_start SMALLINT,     
    IN in_hour_end SMALLINT,       
    IN in_days VARCHAR(25),        
    OUT is_available BOOLEAN       
)
BEGIN
    DECLARE conflicting_teacher INT DEFAULT 0;

    SELECT COUNT(*) 
    INTO conflicting_teacher
    FROM Section s
    JOIN SectionDays sd ON s.section_id = sd.section_id 
    WHERE s.employee_number = in_employee_number
      AND s.hour_start = in_hour_start             
      AND s.hour_end = in_hour_end                  
      AND (
          (sd.Monday = 1 AND FIND_IN_SET('Mon', in_days) > 0) OR
          (sd.Tuesday = 1 AND FIND_IN_SET('Tue', in_days) > 0) OR
          (sd.Wednesday = 1 AND FIND_IN_SET('Wed', in_days) > 0) OR
          (sd.Thursday = 1 AND FIND_IN_SET('Thu', in_days) > 0) OR
          (sd.Friday = 1 AND FIND_IN_SET('Fri', in_days) > 0) OR
          (sd.Saturday = 1 AND FIND_IN_SET('Sat', in_days) > 0)
      );

    IF conflicting_teacher > 0 THEN
        SET is_available = FALSE;  
    ELSE
        SET is_available = TRUE; 
    END IF;

    SELECT is_available AS instructor_availability;
END //

SELECT Employees.employee_number,
       Employees.institute_email,
       Persons.person_id,
       Persons.first_name,
       Persons.last_name,
       Persons.phone,
       Persons.personal_email
FROM Employees
JOIN Persons ON Employees.person_id = Persons.person_id
JOIN Roles ON Employees.role_id = Roles.role_id
WHERE (Employees.employee_number = ? 
       OR Persons.person_id = ? 
       OR Employees.institute_email = ?)
  AND Employees.employee_number = ?; -- Filtro para asegurar que solo se traigan empleados del docente autenticado

SELECT p.first_name, p.last_name, s.account_number
FROM `Students` s 
JOIN `Persons` p ON p.person_id = s.person_id 
WHERE s.account_number = 20201000005;
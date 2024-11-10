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
    DROP TABLE `Employees`;
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
    );
    CREATE TABLE Employees (
        employee_number INT PRIMARY KEY AUTO_INCREMENT,
        person_id VARCHAR(20),
        role_id TINYINT,
        password VARBINARY(255) NOT NULL, 
        institute_email VARCHAR(100) UNIQUE,
        FOREIGN KEY (person_id) REFERENCES Persons(person_id),
        Foreign Key (role_id) REFERENCES Roles(role_id)
    )

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
    Foreign Key (class_id) REFERENCES Classes(class_id),
    Foreign Key (period_id) REFERENCES Periods(period_id),
    Foreign Key (classroom_id) REFERENCES Classroom(classroom_id)
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

    /*modifications for the employee's department*/
    CREATE TABLE Departments (
        department_id INT PRIMARY KEY AUTO_INCREMENT,
        department_name VARCHAR(100) NOT NULL
    );

    ALTER TABLE Employees
    ADD COLUMN department_id INT,
    ADD FOREIGN KEY (department_id) REFERENCES Departments(department_id);

    ALTER TABLE Careers
    ADD COLUMN department_id INT,
    ADD FOREIGN KEY (department_id) REFERENCES Departments(department_id);

    /*end modifications for the employee's department*/

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

        INSERT INTO `Employees` (person_id, role_id, password, institute_email)
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
        OUT out_role VARCHAR(25),
        OUT out_route VARCHAR(25),
        OUT out_employee_number INT       
    )
    BEGIN
        DECLARE secret_key VARCHAR(255);     
        DECLARE db_password VARBINARY(255);
        DECLARE out_employee_number INT;   

        SET is_authenticated = FALSE;
        SET out_role = '0';
        SET out_route = '';
        SET out_employee_number = NULL;

        SELECT JSON_UNQUOTE(JSON_EXTRACT(data, '$.phraseEncrypt'))
        INTO secret_key
        FROM Config
        WHERE config_id = 1;

        SELECT A.password, B.type, B.route,  A.employee_number INTO db_password, out_role, out_route, out_employee_number
        FROM `Employees` A
        INNER JOIN `Roles` B
        ON A.role_id = B.role_id
        WHERE (institute_email = in_identifier OR employee_number = in_identifier);

        IF db_password IS NOT NULL AND AES_DECRYPT(db_password, secret_key) = in_password THEN
            SET is_authenticated = TRUE;
        ELSE
            SET out_role = '0'; 
        END IF;
    END //

    DELIMITER ;



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



/*borrar luego*/
CREATE PROCEDURE LoginAdministrator1 (
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
        SET out_role = '0';
        SET out_route = '';
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
            SET out_role = '0'; 
        END IF;
    END //

    DELIMITER ;


 

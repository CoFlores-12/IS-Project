INSERT INTO `Regional_center`(center_name) VALUES ("CU");
INSERT INTO `Regional_center`(center_name) VALUES ("VS");

INSERT INTO `Faculty`(faculty_name) VALUES ("Ingenieria");

INSERT INTO `Careers`(career_name, faculty_id, time_stm) VALUES ("Ingenieria en sistemas", 1, 5);
INSERT INTO `Careers`(career_name, faculty_id, time_stm) VALUES ("Ingenieria de software", 1, 5);

INSERT INTO `CareersXRegionalCenter` (career_id, center_id) VALUES (1, 1);
INSERT INTO `CareersXRegionalCenter` (career_id, center_id) VALUES (2, 1);
INSERT INTO `CareersXRegionalCenter` (career_id, center_id) VALUES (1, 2);

INSERT INTO `Persons`(person_id, first_name, last_name) VALUES ('admisiones', 'admisiones', "user");
INSERT INTO `Persons`(person_id, first_name, last_name) VALUES ('registro', 'registro', "user");
INSERT INTO `Persons`(person_id, first_name, last_name) VALUES ('admin', 'admin', "user");

INSERT INTO `StatusApplicant` (status_id, description) VALUES (0, 'Pendient');
INSERT INTO `StatusApplicant` (status_id, description) VALUES (1, 'Admitted');
INSERT INTO `StatusApplicant` (status_id, description) VALUES (2, 'Not Admitted');

INSERT INTO `Roles` (role_id, `type`, route) VALUES (0, "Administrator", 'admin');
INSERT INTO `Roles` (role_id, `type`, route) VALUES (1, "Admissions", 'admissions');
INSERT INTO `Roles` (role_id, `type`, route) VALUES (2, "Register Agent", 'register');
INSERT INTO `Roles` (role_id, `type`, route) VALUES (3, "Department Head", 'teacher');
INSERT INTO `Roles` (role_id, `type`, route) VALUES (4, "Coordinator", 'techer');
INSERT INTO `Roles` (role_id, `type`, route) VALUES (5, "Teacher", 'teacher');

CALL `CreateAdministrator`('admin', 0, 'admin', 'admin');
CALL `CreateAdministrator`('admisiones', 1, 'admisiones', 'admisiones@unah.hn');
CALL `CreateAdministrator`('registro', 2, 'registro', 'registro@unah.hn');
INSERT INTO `Persons`(person_id, first_name, last_name) VALUES ('0801202400001', 'docente', "1");
INSERT INTO `Persons`(person_id, first_name, last_name) VALUES ('0801202400002', 'jefe departamento', "1");
INSERT INTO `Persons`(person_id, first_name, last_name) VALUES ('0801202400004', 'coordinador', "1");
CALL `CreateAdministrator`('0801202400001', 5, 'docente', 'docente@unah.hn');
CALL `CreateAdministrator`('0801202400002', 3, 'jefe', 'jefe@unah.hn');
CALL `CreateAdministrator`('0801202400004', 4, 'coordinador', 'coordinador@unah.hn');
INSERT INTO `Persons`(person_id, first_name, last_name) VALUES ('0801202400005', 'estudiante', "1");
INSERT INTO `Students` (account_number, person_id, institute_email, password) VALUES ('20201000005', '0801202400005', 'estudainte@unah.hn', AES_ENCRYPT('estudiante', 'ISPROJECT'));

INSERT INTO `Classroom`(`classroom_id`,`classroom_name`,`building_id`,`capacity`) VALUES (1, 'FI-403', 1, 15);

INSERT INTO `RequestTypes`(request_type_id, title) VALUES (1,'Remedial Exam Fee');
INSERT INTO `RequestTypes`(request_type_id, title) VALUES (2,'Class Cancellation');
INSERT INTO `RequestTypes`(request_type_id, title) VALUES (3,'Career Change');
INSERT INTO `RequestTypes`(request_type_id, title) VALUES (4,'Campus Change');

SELECT C.class_code, C.class_name, A.score FROM History A
INNER JOIN Students B
ON A.student_id = B.account_number
INNER JOIN `Section` S
ON A.section_id = S.section_id
INNER JOIN `Classes` C
ON S.class_id = C.class_id
WHERE B.account_number = '20201000005' OR B.person_id = '0801202400005' OR B.institute_email = 'estudiantes@unah.hn'


/*new*/
INSERT INTO `Departments`(`department_name`) VALUES ("Ingenieria");

INSERT INTO ClassesXCareer (class_id, career_id, req) VALUES (1, 1, '{"id_clase": 1, "id_clase_2": 2}');


SELECT * FROM `Careers` JOIN `ClassesXCareer` ON `Careers`.class_id = `ClassesXCareer`.class_id


INSERT INTO SectionDays (section_id, day) VALUES (17, 'Mon');
INSERT INTO SectionDays (section_id, day) VALUES (17, 'Wed');
INSERT INTO SectionDays (section_id, day) VALUES (17, 'Fri');

 SELECT COUNT(*) 
    FROM Section s
    JOIN SectionDays sd ON s.section_id = sd.section_id
    WHERE s.classroom_id = 1
      AND s.hour_start = 1100       
      AND s.hour_end = 1200      
      AND FIND_IN_SET(sd.day, 'Mon') > 0;  

CALL CheckClassroomAvailability(1, 1100, 1200, 'Mon,Wed,Fri', @availability);
SELECT @availability;

CALL CheckInstructorAvailability(4, 1100, 1200, 'Mon,Wed,Fri', @is_available);

SELECT @is_available; 


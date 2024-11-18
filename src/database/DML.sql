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

SELECT 
    C.class_id,
    C.class_name,
    C.uv,
    C.class_code
FROM 
    Classes C
JOIN 
    ClassesXCareer CC ON C.class_id = CC.class_id
JOIN 
    Careers CR ON CC.career_id = CR.career_id
JOIN 
    Departments D ON CR.department_id = D.department_id
WHERE 
    D.department_id = 1;

SELECT B.*, 
       CASE 
           WHEN A.req IS NULL THEN 1
           WHEN NOT EXISTS (
               SELECT 1
               FROM JSON_TABLE(
                   A.req COLLATE utf8mb4_unicode_ci, '$[*]' COLUMNS (class_code VARCHAR(10) PATH '$')
               ) AS ReqList
               WHERE ReqList.class_code COLLATE utf8mb4_unicode_ci NOT IN (
                   SELECT C.class_code COLLATE utf8mb4_unicode_ci
                   FROM `History` H
                   INNER JOIN `Section` S ON H.section_id = S.section_id
                   INNER JOIN `Classes` C ON S.class_id = C.class_id
                   WHERE H.student_id = 20201000005 AND H.obs_id = 1
               )
           ) THEN 1
           ELSE 0
       END AS estado
FROM `ClassesXCareer` A
INNER JOIN `Classes` B ON A.class_id = B.class_id COLLATE utf8mb4_unicode_ci
WHERE A.career_id = (
    SELECT career_id 
    FROM `Students` 
    WHERE account_number = 20201000005 COLLATE utf8mb4_unicode_ci
)
AND B.class_id NOT IN (
    SELECT S.class_id COLLATE utf8mb4_unicode_ci
    FROM `History` H
    INNER JOIN `Section` S ON H.section_id = S.section_id 
    WHERE H.student_id = 20201000005 AND H.obs_id = 1
)
HAVING estado = 1;

SELECT 
    S.section_id,
    S.hour_start,
    S.days,
    S.quotas,
    P.first_name, 
    P.last_name 
FROM `Section` S
INNER JOIN `Employees` E
ON S.employee_number = `E`.employee_number
INNER JOIN `Persons` P
ON E.person_id = P.person_id
WHERE class_id = 2;

SELECT E.enroll_id, S.hour_start, CONCAT(C.class_code, " ", C.class_name), is_waitlist  
FROM `Enroll` E
INNER JOIN `Section` S
ON E.section_id = S.section_id
INNER JOIN `Classes` C
ON S.class_id = C.class_id
WHERE E.student_id = 20201000005  AND is_cancelled = 0;


SELECT 
    S.section_id,
    S.hour_start,
    S.quotas,
    P.first_name, 
    P.last_name,
    GROUP_CONCAT(
        CASE WHEN SD.Monday = 1 THEN 'Mo ' END,
        CASE WHEN SD.Tuesday = 1 THEN 'Tu ' END,
        CASE WHEN SD.Wednesday = 1 THEN 'We ' END,
        CASE WHEN SD.Thursday = 1 THEN 'Th ' END,
        CASE WHEN SD.Friday = 1 THEN 'Fr ' END,
        CASE WHEN SD.Saturday = 1 THEN 'Sa ' END,
        CASE WHEN `Monday` = 1 THEN 'Mo ' ELSE 'No class' END
    ) AS days_with_classes
FROM `Section` S
INNER JOIN `Employees` E ON S.employee_number = E.employee_number
INNER JOIN `Persons` P ON E.person_id = P.person_id 
INNER JOIN `SectionDays` SD ON S.section_id = SD.section_id
WHERE S.class_id = 1
GROUP BY S.section_id, S.hour_start, S.quotas, P.first_name, P.last_name;

SELECT 
    section_id,
    CONCAT(
        CASE WHEN Monday = 1 THEN 'Mo ' ELSE '' END,
        CASE WHEN Wednesday = 1 THEN 'We ' ELSE '' END,
        CASE WHEN Friday = 1 THEN 'Fr ' ELSE '' END,
        CASE WHEN Tuesday = 1 THEN 'Tu ' ELSE '' END,
        CASE WHEN Thursday = 1 THEN 'Th ' ELSE '' END,
        CASE WHEN Saturday = 1 THEN 'Sa ' ELSE '' END
    ) as Days
FROM `SectionDays`
WHERE section_id = 40;

SELECT section_id, is_waitlist FROM Enroll WHERE enroll_id = 2 AND student_id = 20201000005


SELECT 
    S.quotas - (
        SELECT COUNT(*) FROM `Enroll` E
        WHERE E.section_id = S.section_id AND is_waitlist = 0
    ) as quotas
FROM `Section` S
WHERE section_id = 17;

SELECT 
    A.person_id as identity,
    CONCAT(P.first_name, " ", P.last_name) as full_name,
    A.preferend_career_id,
    C.career_name as preferend_career_name,
    A.secondary_career_id,
    CS.career_name as secondary_career_name
from `Applicant` A
INNER JOIN `Persons` P
On A.person_id = `P`.person_id
INNER JOIN `Careers` C
ON A.preferend_career_id = C.career_id
INNER JOIN `Careers` CS
ON A.secondary_career_id = CS.career_id;

SELECT E.exam_code, EC.career_id, E.exam_name FROM `ExamsXCareer` EC
INNER JOIN `Exams` E ON EC.exam_code = E.exam_code;

SELECT section_id, hour_start, class_code, class_name FROM `Section` S
INNER JOIN `Classes` C
ON S.class_id = C.class_id
WHERE employee_number = 4
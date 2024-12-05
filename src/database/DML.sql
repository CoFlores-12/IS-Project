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
INSERT INTO `Persons`(person_id, first_name, last_name, personal_email) VALUES ('0801202400005', 'estudiante', "1", "daavilav@unah.hn");
INSERT INTO `Students` (account_number, person_id, institute_email, password, career_id) VALUES ('20201000004', '0801202400004', 'prueba@unah.hn', AES_ENCRYPT('prueba', 'ISPROJECT'), 1);

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
      AND (
          (sd.Monday = 1 AND FIND_IN_SET('Mon', 'Mon,Wed,Fri') > 0) OR
          (sd.Tuesday = 1 AND FIND_IN_SET('Tue', 'Mon,Wed,Fri') > 0) OR
          (sd.Wednesday = 1 AND FIND_IN_SET('Wed', 'Mon,Wed,Fri') > 0) OR
          (sd.Thursday = 1 AND FIND_IN_SET('Thu', 'Mon,Wed,Fri') > 0) OR
          (sd.Friday = 1 AND FIND_IN_SET('Fri', 'Mon,Wed,Fri') > 0) OR
          (sd.Saturday = 1 AND FIND_IN_SET('Sat', 'Mon,Wed,Fri') > 0)
      );


CALL CheckClassroomAvailability(1, 1000, 1100, 'Mon,Wed,Fri', @availability);
SELECT @availability;

CALL CheckInstructorAvailability(4, 0900, 1000, 'Mon,Wed,Fri', @is_available);

SELECT @is_available; 

SELECT capacity FROM Classroom WHERE classroom_id = 1;

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
    s.section_id,
    c.class_name,
    b.building_name,
    s.hour_start,
    s.hour_end,
    s.period_id,
    s.classroom_id,
    cr.classroom_name,
    s.quotas,
    sd.Monday,
    sd.Tuesday,
    sd.Wednesday,
    sd.Thursday,
    sd.Friday,
    sd.Saturday,
    (SELECT COUNT(*) 
     FROM Enroll e
     WHERE e.section_id = s.section_id) AS enrolled_students
FROM 
    Section s
INNER JOIN 
    SectionDays sd ON s.section_id = sd.section_id
INNER JOIN 
    Employees e_section ON s.employee_number = e_section.employee_number
INNER JOIN 
    Classes c ON s.class_id = c.class_id
INNER JOIN 
    Classroom cr ON s.classroom_id = cr.classroom_id
INNER JOIN 
    Building b ON cr.building_id = b.building_id
WHERE 
    e_section.department_id = (
        SELECT department_id
        FROM Employees e
        WHERE e.employee_number = 5
    );

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

INNER JOIN `Exams` E ON EC.exam_code = E.exam_code
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
INNER JOIN `Exams` E ON EC.exam_code = E.exam_code
INNER JOIN `Exams` E ON EC.exam_code = E.exam_code;

SELECT section_id, hour_start, class_code, class_name FROM `Section` S
INNER JOIN `Classes` C
ON S.class_id = C.class_id
WHERE employee_number = 4;

SELECT 
    A.person_id as identity,
    CONCAT(P.first_name, " ", P.last_name) as full_name,
    A.certify
from `Applicant` A
INNER JOIN `Persons` P
On A.person_id = `P`.person_id
WHERE status_id = 0 AND validated IS NULL;

UPDATE `Applicant` SET validated = NULL;


SELECT role_id FROM `LogAuth` GROUP BY role_id;

INSERT INTO obsReviews (comment) VALUES ('Primer nombre incorrecto');
INSERT INTO obsReviews (comment) VALUES ('Segundo nombre incorrecto');
INSERT INTO obsReviews (comment) VALUES ('Primer Apellido incorrecto');
INSERT INTO obsReviews (comment) VALUES ('Segundo Apellido incorrecto');
INSERT INTO obsReviews (comment) VALUES ('Foto de certificado borrosa');
INSERT INTO obsReviews (comment) VALUES ('Documento incompleto');
INSERT INTO obsReviews (comment) VALUES ('Error en el número de identificación');
INSERT INTO obsReviews (comment) VALUES ('Inconsistencia en la información proporcionada');
INSERT INTO obsReviews (comment) VALUES ('Certificado con datos ilegibles');
INSERT INTO obsReviews (comment) VALUES ('Faltan firmas o sellos en el documento');
INSERT INTO obsReviews (comment) VALUES ('Datos falsificados detectados');


SELECT p.first_name, p.personal_email FROM Applicant a INNER JOIN `Persons` p ON a.person_id = p.person_id;

WITH LastMessage AS (
    SELECT
        chat_id,
        MAX(sent_at) AS last_message_time
    FROM Messages
    GROUP BY chat_id
),
MessageDetails AS (
    SELECT
        m.chat_id,
        m.content AS last_message,
        m.sent_at AS message_time
    FROM Messages m
    INNER JOIN LastMessage lm ON m.chat_id = lm.chat_id AND m.sent_at = lm.last_message_time
)
SELECT
    c.chat_id,
    c.is_group,
    -- Datos para grupos
    g.group_name AS group_name,
    g.group_photo AS group_photo,
    -- Datos para chats directos
    p.first_name AS direct_user_name,
    -- Último mensaje
    md.last_message,
    md.message_time
FROM Chats c
LEFT JOIN ChatsGroups g ON c.group_id = g.group_id
LEFT JOIN ChatParticipants cp ON c.chat_id = cp.chat_id
LEFT JOIN Persons p ON p.person_id = (
    SELECT person_id
    FROM ChatParticipants
    WHERE 
        (chat_id = c.chat_id AND person_id != (SELECT person_id FROM `Students` WHERE account_number = 20201000005))
    OR 
        (chat_id = c.chat_id AND person_id != (SELECT person_id FROM `Employees` WHERE employee_number = 9))
    LIMIT 1
)
LEFT JOIN MessageDetails md ON c.chat_id = md.chat_id
WHERE 
    cp.person_id = (SELECT person_id FROM `Students` WHERE account_number = 20201000005)
OR
    cp.person_id = (SELECT person_id FROM `Employees` WHERE employee_number = 9)
ORDER BY md.message_time DESC;


SELECT person_id FROM `Persons` p
WHERE p.person_id = (SELECT person_id FROM `Students` WHERE account_number = NULL)
    OR 
    p.person_id = (SELECT person_id FROM `Employees` WHERE employee_number = 9)
;

SELECT COUNT(*)
        FROM Messages m
        WHERE 
            m.chat_id = 2 
            AND (m.status = 0 OR m.status = 1) 
            AND ( m.sender_id != (SELECT person_id FROM `Students` WHERE account_number = ?)
                OR m.sender_id != (SELECT person_id FROM `Employees` WHERE employee_number = ?));


SELECT 
    c.chat_id,
    c.is_group,
    COALESCE(cg.group_name, CONCAT(p.first_name, ' ', p.last_name)) AS chat_name,
    MAX(la.DATE) AS last_connection
FROM Chats c
LEFT JOIN ChatsGroups cg ON c.group_id = cg.group_id
LEFT JOIN ChatParticipants cp ON c.chat_id = cp.chat_id
LEFT JOIN Persons p ON cp.person_id = p.person_id
LEFT JOIN LogAuth la ON p.person_id = la.identifier
WHERE c.chat_id = 2
  AND (c.is_group = 1 OR cp.person_id != 0801202400005)
GROUP BY c.chat_id, c.is_group, chat_name;


SELECT 
    h.student_id,
    SUM(h.score * c.uv) / SUM(c.uv) AS indice_global,
    (SELECT 
        SUM(h1.score * c1.uv) / SUM(c1.uv)  
     FROM 
        History h1
     JOIN 
        `Section` s1 ON h1.section_id = s1.section_id
     JOIN 
        `Classes` c1 ON s1.class_id = c1.class_id
     JOIN 
        `Periods` p1 ON s1.period_id = p1.period_id
     WHERE 
        h1.student_id = h.student_id
        AND p1.active = 0 
        AND s1.period_id = (
            SELECT MAX(period_id)  
            FROM `Periods`
            WHERE active = 0
        )
    ) AS indice_ultimo_periodo
FROM 
    History h
JOIN 
    `Section` s ON h.section_id = s.section_id
JOIN 
    `Classes` c ON s.class_id = c.class_id
WHERE 
    h.student_id = '20201000005' 
GROUP BY 
    h.student_id;


SELECT * FROM  `Requests` r
INNER JOIN `Periods` p on r.period_id = p.period_id
WHERE p.active = 1 AND  r.student_id = 20201000005




SELECT 
    e.student_id,
    COUNT(h.history_id) AS approved_classes
FROM Enroll e
JOIN Students st ON e.student_id = st.account_number
LEFT JOIN History h ON e.student_id = h.student_id AND h.obs_id = 1
WHERE e.section_id = 159
GROUP BY e.student_id;

SELECT 
    e.student_id,
    COUNT(h.history_id) AS approved_classes,
    total_classes.total_classes - COUNT(h.history_id) AS pending_classes,
    CASE 
        WHEN total_classes.total_classes - COUNT(h.history_id) < 5 THEN TRUE
        ELSE FALSE
    END AS has_less_than_5_remaining
FROM Enroll e
JOIN Students st ON e.student_id = st.account_number
LEFT JOIN History h ON e.student_id = h.student_id AND h.obs_id = 1
JOIN (
    SELECT 
        cxc.career_id,
        COUNT(cxc.class_id) AS total_classes
    FROM ClassesXCareer cxc
    GROUP BY cxc.career_id
) AS total_classes ON st.career_id = total_classes.career_id
WHERE e.section_id = 159
GROUP BY e.student_id, total_classes.total_classes;


SELECT 
    s.section_id,
    c.class_code,
    c.class_name,
    e.employee_number,
    CONCAT(p.first_name, " ", p.last_name) as teacher,
    (
        SELECT COUNT(*) FROM `Enroll` en
        WHERE en.section_id = s.section_id
    ) as enrolled,
    s.quotas,
    cr.classroom_name,
    b.building_name
FROM `Section` s
INNER JOIN `Classes` c 
ON s.class_id = c.class_id
INNER JOIN `Employees` e 
ON s.employee_number = e.employee_number
INNER JOIN `Persons` p 
ON e.person_id = p.person_id
INNER JOIN `Classroom` cr 
ON s.classroom_id = cr.classroom_id
INNER JOIN `Building` b 
ON cr.building_id = b.building_id
INNER JOIN `Periods` pe
ON s.period_id = pe.period_id
INNER JOIN `ClassesXCareer` cxc 
ON c.class_id = cxc.class_id
INNER JOIN `Careers` ca
ON cxc.career_id = ca.career_id
WHERE pe.active = 1 AND ca.department_id = (
    SELECT department_id FROM `Employees` WHERE employee_number = 9
);


SELECT p.first_name, p.personal_email, rt.title
    FROM Requests r 
    INNER JOIN `RequestTypes` rt ON r.request_type_id = rt.request_type_id
    INNER JOIN `Students` s ON r.student_id = s.account_number 
    INNER JOIN `Persons` p ON s.person_id = p.person_id
    WHERE r.request_id = 1;

SELECT 
    CONCAT(p.first_name, " ", p.last_name, "(", s.account_number, ")") as student,
    e.score,
    e.obs_id
FROM `Enroll` e
INNER JOIN `Students` s 
ON e.student_id = s.account_number
INNER JOIN `Persons` p 
ON s.person_id = p.person_id
WHERE e.section_id = 168;

SELECT 
    e.score,
    o.obs_name
    FROM `Enroll` e
    INNER JOIN `Students` s 
    ON e.student_id = s.account_number
    INNER JOIN `Persons` p 
    ON s.person_id = p.person_id
    LEFT JOIN Obs o
    ON e.obs_id = o.obs_id
    WHERE e.section_id = 168 AND e.student_id = 20201000005;

SELECT e.score, e.obs_id, p.personal_email, class_name
FROM Enroll e
INNER JOIN `Students` s ON e.student_id = s.account_number
INNER JOIN `Persons` p ON s.person_id = p.person_id
INNER JOIN `Section` se ON e.section_id = se.section_id
INNER JOIN `Classes` c ON se.class_id = c.class_id
WHERE e.section_id = 168 AND e.student_id = 20201000005;






SELECT COUNT(*) FROM `Students` s
INNER JOIN `Careers` c ON s.career_id = c.career_id
WHERE c.department_id = (
    SELECT department_id FROM `Employees` WHERE employee_number = 9
);

SELECT COUNT(*) FROM `Employees`
WHERE department_id = (
    SELECT department_id FROM `Employees` WHERE employee_number = 9
);

SELECT COUNT(*) FROM
`ClassesXCareer` cl 
INNER JOIN `Careers` c ON cl.career_id = c.career_id
WHERE c.department_id = (
    SELECT department_id FROM `Employees` WHERE employee_number = 9
);

SELECT 
    SUM(CASE WHEN h.obs_id = 1 THEN 1 ELSE 0 END) AS APB,
    SUM(CASE WHEN h.obs_id = 0 THEN 1 ELSE 0 END) AS RPB,
    SUM(CASE WHEN h.obs_id = 2 THEN 1 ELSE 0 END) AS ABD,
    SUM(CASE WHEN h.obs_id = 3 THEN 1 ELSE 0 END) AS NSP
FROM 
    History h
JOIN 
    Section s ON h.section_id = s.section_id
JOIN `ClassesXCareer` cxc ON s.class_id = cxc.class_id
INNER JOIN `Careers` c ON cxc.career_id = c.career_id
WHERE c.department_id = (
    SELECT department_id FROM `Employees` WHERE employee_number = 9
);

SELECT 
    s.period_id,
    AVG(h.score) AS average_score
FROM 
    History h
JOIN 
    Section s ON h.section_id = s.section_id
JOIN 
    `ClassesXCareer` cxc ON s.class_id = cxc.class_id
INNER JOIN 
    `Careers` c ON cxc.career_id = c.career_id
WHERE c.department_id = (
    SELECT department_id FROM `Employees` WHERE employee_number = 9
)
GROUP BY 
    s.period_id
LIMIT 9;


INSERT INTO `Persons` (person_id, first_name, last_name, personal_email) VALUES 
('0801202111111', 'Estudiante', '1.1', 'daavilav@unah.hn'),
('0801202400012', 'Estudiante', '2', 'daavilav@unah.hn'),
('0801202400013', 'Estudiante', '3', 'daavilav@unah.hn'),
('0801202400014', 'Estudiante', '4', 'daavilav@unah.hn'),
('0801202400015', 'Estudiante', '5', 'daavilav@unah.hn'),
('0801202400016', 'Estudiante', '6', 'daavilav@unah.hn'),
('0801202400017', 'Estudiante', '7', 'daavilav@unah.hn'),
('0801202400018', 'Estudiante', '8', 'daavilav@unah.hn'),
('0801202400019', 'Estudiante', '9', 'daavilav@unah.hn'),
('0801202401110', 'Estudiante', '10', 'daavilav@unah.hn');

INSERT INTO `Students` (account_number, person_id, institute_email, password, career_id) VALUES 
('20211000001', '0801202111111', 'prueba1@unah.hn', AES_ENCRYPT('prueba', 'ISPROJECT'), 1),
('20211000002', '0801202400012', 'prueba2@unah.hn', AES_ENCRYPT('prueba', 'ISPROJECT'), 1),
('20211000003', '0801202400013', 'prueba3@unah.hn', AES_ENCRYPT('prueba', 'ISPROJECT'), 1),
('20211000004', '0801202400014', 'prueba4@unah.hn', AES_ENCRYPT('prueba', 'ISPROJECT'), 1),
('20211000005', '0801202400015', 'prueba5@unah.hn', AES_ENCRYPT('prueba', 'ISPROJECT'), 1),
('20211000006', '0801202400016', 'prueba6@unah.hn', AES_ENCRYPT('prueba', 'ISPROJECT'), 1),
('20211000007', '0801202400017', 'prueba7@unah.hn', AES_ENCRYPT('prueba', 'ISPROJECT'), 1),
('20211000008', '0801202400018', 'prueba8@unah.hn', AES_ENCRYPT('prueba', 'ISPROJECT'), 1),
('20211000009', '0801202400019', 'prueba9@unah.hn', AES_ENCRYPT('prueba', 'ISPROJECT'), 1),
('20211000010', '0801202401110', 'prueba10@unah.hn', AES_ENCRYPT('prueba', 'ISPROJECT'), 1);

INSERT INTO 
  `Section` (
    class_id, 
    hour_start, 
    hour_end, 
    period_id, 
    classroom_id, 
    employee_number, 
    quotas
  )
VALUES
  (1, 1400, 1500, 4, 1, 5, 12),
  (7, 0900, 1000, 5, 1, 4, 15),
  (23, 1100, 1200, 6, 1, 9, 14),
  (12, 0800, 0900, 7, 1, 5, 10),
  (34, 1300, 1400, 9, 1, 4, 13),
  (45, 1600, 1700, 8, 1, 9, 14),
  (6, 1000, 1100, 5, 1, 5, 11),
  (25, 1500, 1600, 6, 1, 4, 15),
  (14, 1700, 1800, 7, 1, 9, 10),
  (36, 1400, 1500, 9, 1, 5, 12),
  (41, 1200, 1300, 4, 1, 4, 13),
  (18, 1300, 1400, 5, 1, 9, 11),
  (29, 0900, 1000, 6, 1, 5, 15),
  (9, 1100, 1200, 7, 1, 4, 10),
  (49, 0800, 0900, 8, 1, 9, 14),
  (32, 1600, 1700, 9, 1, 5, 12),
  (4, 1500, 1600, 4, 1, 4, 13),
  (21, 1400, 1500, 5, 1, 9, 10),
  (38, 1200, 1300, 6, 1, 5, 14),
  (50, 1100, 1200, 7, 1, 4, 15);

INSERT INTO 
  `History` (
    section_id, 
    student_id, 
    score, 
    obs_id
  )
VALUES
  (189, '20211000001', 70, 1),
  (190, '20211000002', 85, 1),
  (191, '20211000003', 60, 2),
  (192, '20211000004', 90, 1),
  (193, '20211000005', 50, 2),
  (194, '20211000006', 95, 1),
  (195, '20211000007', 80, 1),
  (196, '20211000008', 40, 2),
  (197, '20211000009', 0, 3),
  (198, '20211000010', 67, 1),
  (199, '20211000001', 100, 1),
  (200, '20211000002', 33, 2),
  (201, '20211000003', 54, 2),
  (202, '20211000004', 80, 1),
  (203, '20211000005', 23, 2),
  (204, '20211000006', 60, 2),
  (205, '20211000007', 72, 1),
  (206, '20211000008', 10, 2),
  (207, '20211000009', 65, 1),
  (208, '20211000010', 45, 2);

INSERT INTO `Periods` (indicator, year, active) VALUES 
    (1, "2022", 0),
    (2, "2022", 0),
    (3, "2022", 0),
    (1, "2023", 0),
    (2, "2023", 0),
    (3, "2023", 0),
    (1, "2024", 0),
    (2, "2024", 0),
    (3, "2024", 1);

SELECT 
    SUM(h.score * c.uv) / SUM(c.uv) AS indice_global,
    (SELECT 
        SUM(h1.score * c1.uv) / SUM(c1.uv)  
     FROM 
        History h1
     JOIN 
        `Section` s1 ON h1.section_id = s1.section_id
     JOIN 
        `Classes` c1 ON s1.class_id = c1.class_id
     JOIN 
        `Periods` p1 ON s1.period_id = p1.period_id
     WHERE 
        h1.student_id = h.student_id
        AND s1.period_id = (
            SELECT MAX(period_id)  
            FROM `Periods`
            WHERE active = 0
        )
    ) AS indice_ultimo_periodo
    FROM 
        History h
    JOIN 
        `Section` s ON h.section_id = s.section_id
    JOIN 
        `Classes` c ON s.class_id = c.class_id
    WHERE 
        h.student_id = 20201000005
    GROUP BY 
        h.student_id;

SELECT 
    r.request_id,
    r.student_id,
    CONVERT_TZ(r.date, '+00:00', '-06:00') AS local_time, 
    rt.title,
    CONCAT(p.indicator, ' ', p.year) AS period
FROM `Requests` r
INNER JOIN RequestTypes rt ON r.request_type_id = rt.request_type_id
INNER JOIN `Periods` p ON r.period_id = p.period_id
INNER JOIN `CareersXRegionalCenter` crc ON crc.career_id = (
    SELECT student.career_id 
    FROM Students student 
    WHERE student.account_number = r.student_id
)
WHERE r.status IS NULL 
  AND r.request_type_id = 2
  AND p.active = 1
  AND crc.coordinator_id = :coordinator_id;

SELECT 
    r.request_id,
    r.student_id,
    CONVERT_TZ(r.date, '+00:00', '-06:00') AS local_time, 
    rt.title,
    CONCAT(p.indicator, ' ', p.year) AS period
FROM `Requests` r
INNER JOIN RequestTypes rt ON r.request_type_id = rt.request_type_id
INNER JOIN `Periods` p ON r.period_id = p.period_id
INNER JOIN `CareersXRegionalCenter` crc ON crc.career_id = r.career_change_id
INNER JOIN `Persons` pr ON pr.center_id = crc.center_id
INNER JOIN `Employees` emp ON emp.person_id = pr.person_id
WHERE r.status IS NULL 
  AND r.request_type_id = 3
  AND p.active = 1
  AND crc.coordinator_id = :coordinator_id
  AND emp.employee_number = :coordinator_id;

SELECT 
    r.request_id,
    r.student_id,
    CONVERT_TZ(r.date, '+00:00', '-06:00') AS local_time, 
    rt.title,
    CONCAT(p.indicator, ' ', p.year) AS period
FROM `Requests` r
INNER JOIN RequestTypes rt ON r.request_type_id = rt.request_type_id
INNER JOIN `Periods` p ON r.period_id = p.period_id
INNER JOIN `Persons` pr ON pr.center_id = r.campus_change_id
INNER JOIN `Employees` emp ON emp.person_id = pr.person_id
INNER JOIN `CareersXRegionalCenter` crc ON crc.career_id = (
    SELECT student.career_id 
    FROM Students student 
    WHERE student.account_number = r.student_id
)
WHERE r.status IS NULL 
  AND r.request_type_id = 4
  AND p.active = 1
  AND emp.employee_number = :coordinator_id;

  SELECT ap.* FROM 
    Students s
    inner join Persons p ON s.person_id = p.person_id
    inner join Applicant_result ap on p.person_id = ap.identity_number
    WHERE s.account_number = 20211000002
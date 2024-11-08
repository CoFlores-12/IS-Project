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
INSERT INTO `StatusApplicant` (status_id, description) VALUES (3, 'Not Admitted');
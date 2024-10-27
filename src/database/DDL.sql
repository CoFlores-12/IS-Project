
CREATE TABLE RegionalCenter (
    center_id INT PRIMARY KEY AUTO_INCREMENT,
    center_name VARCHAR(100) NOT NULL
);

CREATE TABLE Applicant (
    applicant_id INT PRIMARY KEY AUTO_INCREMENT,
    identity_number VARCHAR(20) NOT NULL UNIQUE,       
    first_name VARCHAR(50) NOT NULL,                   
    last_name VARCHAR(50) NOT NULL,                    
    phone VARCHAR(15),                                 
    email VARCHAR(100) NOT NULL,                       
    preferred_career VARCHAR(100) NOT NULL,            
    secondary_career VARCHAR(100),
    regional_center INT,                
    application_status ENUM('Pending', 'Accepted', 'Rejected') DEFAULT 'Pending',
    exam_score DECIMAL(5, 2),
    preferred_career_passed BOOLEAN DEFAULT FALSE,
    secondary_career_passed BOOLEAN DEFAULT FALSE,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    document_photo VARCHAR(255),                               
    result_notification_sent BOOLEAN DEFAULT FALSE,
    CONSTRAINT FK_RegionalCenter FOREIGN KEY (regional_center)
    REFERENCES RegionalCenter(center_id) 
);


CREATE TABLE User (
    user_id VARCHAR(25) PRIMARY KEY,
    identity_number VARCHAR(20) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    personal_email VARCHAR(100) NOT NULL UNIQUE,
    institutional_email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(15),
    role ENUM('Student', 'Teacher', 'Admin') NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_access TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    status ENUM('Active', 'Inactive') DEFAULT 'Active',
    
    CONSTRAINT UC_IdentityNumber UNIQUE (identity_number) 
);

CREATE TABLE employees (
    user_id VARCHAR(25),
    
)

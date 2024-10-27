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
